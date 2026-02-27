<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Encryptable;

    protected $table = 'Customers';

    protected $primaryKey = 'CustomerId';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'UserId',
        'Name',
        'DateOfBirth',
        'Gender',
        'AllergyNotes',
        'CoinBalance',
        'MembershipTier',
        'Location',
    ];

    protected $encrypted = [
        'Name',
        'AllergyNotes',
        'Location'
    ];

    /**
     * Global attribute interceptor (BEST ENTERPRISE WAY)
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (isset($this->encrypted) && in_array($key, $this->encrypted)) {
            return $this->decryptAttribute($value);
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (isset($this->encrypted) && in_array($key, $this->encrypted)) {
            $value = $this->encryptAttribute($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'CustomerId', 'CustomerId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'UserId');
    }
}