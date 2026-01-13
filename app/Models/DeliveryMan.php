<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    // Standard snake_case table name (from migrations)
     protected $table = 'DeliveryMen';

    // Primary key is UUID
    protected $primaryKey = 'DeliveryManId';

    public $incrementing = false; // if using UUID
    protected $keyType = 'string'; // if UUID

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'UserId',
        'VehicleType',
        'VehicleNumber',
        'LicenseNumber',
        'Lattitude',
        'Longitude',
        'IsOnline',
        'MaxConcurrentDeliveries',
        'TotalDeliveries',
        'CompletedDeliveries',
        'LastActiveAt',
    ];

    protected $casts = [
        'CreatedAt'    => 'datetime',
        'CancelledAt'  => 'datetime',
        'LastActiveAt'  => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->DeliveryManId) {
                $model->DeliveryManId = (string) Str::uuid();
            }
        });
    }

    public function assignedOrder()
    {
        return $this->hasMany(Order::class, 'DeliveryManId', 'DeliveryManId'); //First is related model, second is foreign key in Order, third is local key in DeliveryMan
    }

    public function user() {
        return $this->belongsTo(User::class, 'UserId', 'UserId');//Second is foreign key in deliveryMan, third is local key in User
    }
}
