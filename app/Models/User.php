<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Encryptable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Encryptable;

    protected $table = 'Users';

    protected $primaryKey = 'UserId';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    /*
    |--------------------------------------------------------------------------
    | Fillable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'Role',
        'Name',
        'Email',
        'PasswordHash',
        'Phone',
        'AvatarUrl',
        'IsActive',
        'IsEmailVerified',
        'DeletedAt',
        'IsBusinessAdmin',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Encryption Column List
    |--------------------------------------------------------------------------
    */
    protected $encrypted = [
        'Name',
        'Email',
        'Phone'
    ];

    /*
    |--------------------------------------------------------------------------
    | Universal Safe Attribute Getter (BEST PRACTICE )
    |--------------------------------------------------------------------------
    */

    // Decrypt Name
    public function getNameAttribute($value)
    {
        return $this->decryptAttribute($value);
    }

    // Decrypt Email
    public function getEmailAttribute($value)
    {
        return $this->decryptAttribute($value);
    }

    // Decrypt Phone
    public function getPhoneAttribute($value)
    {
        return $this->decryptAttribute($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators (Encryption Writing)
    |--------------------------------------------------------------------------
    */

    public function setNameAttribute($value)
    {
        $this->attributes['Name'] = $this->encryptAttribute($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['Email'] = $this->encryptAttribute($value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['Phone'] = $this->encryptAttribute($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Safe Decryption Handler (PostgreSQL bytea Compatible)
    |--------------------------------------------------------------------------
    */

    protected function decryptSafe($value)
    {
        if (!$value) {
            return null;
        }

        try {

            if (is_resource($value)) {
                $value = stream_get_contents($value);
            }

            if (!is_string($value)) {
                return null;
            }

            return app(\App\Services\AesEncryptionService::class)
                ->decrypt($value);

        } catch (\Throwable $e) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Password Column
    |--------------------------------------------------------------------------
    */

    public function getAuthPassword()
    {
        return $this->attributes['PasswordHash'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->hasOne(Customer::class, 'UserId', 'UserId');
    }

    public function deliveryMan()
    {
        return $this->hasOne(DeliveryMan::class, 'UserId', 'UserId');
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'UserId', 'UserId');
    }

    public function medicalstores()
    {
        return $this->hasMany(MedicalStore::class, 'UserId', 'UserId');
    }
}