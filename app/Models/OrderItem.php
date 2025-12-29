<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'OrderItems';
    protected $primaryKey = 'OrderItemId';
    public $incrementing = false; // if using UUID
    protected $keyType = 'string'; // if UUID

    protected $fillable = [
        'order_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderId', 'OrderId'); //Second is foreign key in OrderItem, third is local key in Order
    }
}
