<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Standard snake_case table name (from migrations)
    protected $table = 'order_items';

    // Primary key is UUID
    protected $primaryKey = 'OrderItemId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;

    protected $fillable = [
        'OrderId',
        'ItemId',
        'ItemType',
        'ItemName',
        'ItemImageUrl',
        'Quantity',
        'UnitPrice',
        'BusinessId',
        'BusinessType',
        'ForwardedAt',
        'AssignedByAdmin',
        'Status',
        'AcceptedAt',
        'ReadyAt',
        'CompletedAt',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
