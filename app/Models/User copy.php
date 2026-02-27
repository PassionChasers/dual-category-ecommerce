<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Traits\Encryptable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Encryptable;

    /**
     * Primary key is UUID
     */
    // protected $primaryKey = 'id';
    public $incrementing = false;       // not auto-incrementing
    protected $keyType = 'string';      // stored as string

    protected $table = 'Users';

    public $timestamps = true;

    // Primary key (assumed)
    protected $primaryKey = 'UserId'; // change if different
    
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    // PostgreSQL case-sensitive columns
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

    protected $encrypted = [
        'name',
        'email',
        'phone_number'
    ];


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

       /**
     * Tell Laravel which column stores the password
     */
    public function getAuthPassword()
    {
        return $this->attributes['PasswordHash'];
    }

    /**
     * Accessor for auth()->user()->email
     */
    public function getEmailAttribute()
    {
        return $this->attributes['Email'] ?? null;
    }

    /**
     * Accessor for auth()->user()->name
     */
    public function getNameAttribute()
    {
        return $this->attributes['Name'] ?? null;
    }

    // User → Customer
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