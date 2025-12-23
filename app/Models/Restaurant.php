<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
     protected $table = 'restaurants';

    // primary key is UUID non-incrementing
    protected $primaryKey = 'RestaurantId';
    public $incrementing = false;
    protected $keyType = 'string';

    // if you want Laravel to manage timestamps, keep $timestamps = true
    public $timestamps = true;

    protected $fillable = [
        'RestaurantId',
        'UserId',
        'Name',
        'Slug',
        'LicenseNumber',
        'GSTIN',
        'PAN',
        'IsActive',
        'IsFeatured',
        'OpenTime',
        'CloseTime',
        'RadiusKm',
        'DeliveryFee',
        'MinOrder',
        'Latitude',
        'Longitude',
        'Priority',
        'ImageUrl',
        'CreatedAt'
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

    // If you need a slug auto-generation helper
    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->RestaurantId)) {
                $model->RestaurantId = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($model->CreatedAt)) {
                $model->CreatedAt = now();
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
        return $this->belongsTo(User::class, 'UserId', 'id');
    }
}
