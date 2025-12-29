<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $primaryKey = 'CustomerId';
    
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // Specify custom table name
    protected $table = 'Customers';

    public function order()
    {
        return $this->hasMany(Order::class, 'CustomerId', 'CustomerId'); //First is related model(Order), second is foreign key in Order, third is local key in Order
    }

    public function user() {
        return $this->belongsTo(User::class, 'UserId', 'UserId');//Second is foreign key in Customer, third is local key in User
    }
}
