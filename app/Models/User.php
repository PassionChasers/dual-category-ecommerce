<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Primary key is UUID
     */
    // protected $primaryKey = 'id';
    public $incrementing = false;       // not auto-incrementing
    protected $keyType = 'string';      // stored as string

    protected $table = 'Users';

    // Primary key (assumed)
    protected $primaryKey = 'UserId'; // change if different

    // PostgreSQL case-sensitive columns
    protected $fillable = [
        'Name',
        'Email',
        'PasswordHash',
        'Phone',
        'AvatarUrl',
    ];

    // Laravel should not expect created_at / updated_at
    public $timestamps = false;

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

    
}