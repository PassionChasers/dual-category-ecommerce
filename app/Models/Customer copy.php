<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // Table name with capital first letter
    protected $table = 'Customers';

    // Primary key is UUID
    protected $primaryKey = 'CustomerId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (use custom column names)
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // All user-assignable columns (exclude primary key and timestamps)
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

    public function order()
    {
        return $this->hasMany(Order::class, 'CustomerId', 'CustomerId'); //First is related model(Order), second is foreign key in Order, third is local key in Order
    }

    public function user() {
        return $this->belongsTo(User::class, 'UserId', 'UserId');//Second is foreign key in Customer, third is local key in User
    }
}
