<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalStore extends Model
{
    // Table name with capital first letter
    protected $table = 'MedicalStores';

    // Primary key is UUID non-incrementing
    protected $primaryKey = 'MedicalStoreId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (uses created_at & updated_at)
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'UserId',
        'Name',
        'Slug',
        'LicenseNumber',
        'GSTIN',
        'PAN',
        'IsActive',
        'OpenTime',
        'CloseTime',
        'RadiusKm',
        'DeliveryFee',
        'MinOrder',
        'Latitude',
        'Longitude',
        'Priority',
    ];

    // Casts
    protected $casts = [
        'IsActive' => 'boolean',
        'IsFeatured' => 'boolean',
        'OpenTime' => 'string',
        'CloseTime' => 'string',
        'RadiusKm' => 'decimal:2',
        'DeliveryFee' => 'decimal:2',
        'MinOrder' => 'decimal:2',
        'Latitude' => 'decimal:7',
        'Longitude' => 'decimal:7',
        'Priority' => 'integer',
    ];

    // Boot method for UUID generation and slug
    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->MedicalStoreId)) {
                $model->MedicalStoreId = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($model->Slug) && !empty($model->Name)) {
                $model->Slug = \Illuminate\Support\Str::slug($model->Name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->Slug) && !empty($model->Name)) {
                $model->Slug = \Illuminate\Support\Str::slug($model->Name);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'UserId');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'BusinessId', 'MedicalStoreId'); //First is related model(OrderItem), second is foreign key in OrderItem, third is local key in MedicalStore
    }

}
