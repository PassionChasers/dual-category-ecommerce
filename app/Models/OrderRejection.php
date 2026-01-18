<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class OrderRejection extends Model
{
    // Specify custom table name
    protected $table = 'OrderRejections';

    // Primary key is UUID
    protected $primaryKey = 'OrderRejectionId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = false;

    // const CREATED_AT = 'CreatedAt';
    // const UPDATED_AT = null;

    protected $fillable = [
        'OrderId',
        'BusinessId',
        'BusinessType',
        'RejectionReason',
        'RejectedAt'
    ];

    protected $casts = [
        'RejectedAt'    => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->OrderRejectionId) {
                $model->OrderRejectionId = (string) Str::uuid();
            }

            if (!$model->RejectedAt) {
                $model->RejectedAt = now();
            }
        });
    }

    public function medicalstoreBusiness() {
        return $this->belongsTo(MedicalStore::class, 'MedicalStoreId', 'BusinessId');
    }

    public function restaurantBusiness() {
        return $this->belongsTo(Restaurant::class, 'RestaurantId', 'BusinessId');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'OrderId', 'OrderId');
    }

}
