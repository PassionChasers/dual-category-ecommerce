<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Standard snake_case table name (from migrations)
     protected $table = 'OrderItems';

    // Primary key is UUID
    protected $primaryKey = 'OrderItemId';

    public $incrementing = false; // if using UUID
    protected $keyType = 'string'; // if UUID

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

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
        return $this->belongsTo(Order::class, 'OrderId', 'OrderId'); //Second is foreign key in OrderItem, third is local key in Order
    }

    public function medicalStore()
    {
        return $this->belongsTo(MedicalStore::class, 'BusinessId', 'MedicalStoreId'); //Second is foreign key in OrderItem, third is local key in MedicalStore
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'BusinessId', 'RestaurantId'); //Second is foreign key in OrderItem, third is local key in Restaurants
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class, 'MedicineId', 'MedicineId');
    }

    public function food() {
        return $this->belongsTo(MenuItem::class, 'MenuItemId', 'MenuItemId');
    }
}
