<?php

namespace App\Models;

use GuzzleHttp\Promise\Is;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    // Table name with capital first letter
    protected $table = 'Restaurants';

    // Primary key is UUID non-incrementing
    protected $primaryKey = 'RestaurantId';
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
        'IsPureVeg',
        'CuisineType',
        'PrepTimeMin',
        'PAN',
        'OpenTime',
        'CloseTime',
        'RadiusKm',
        'DeliveryFee',
        'MinOrder',
        'Latitude',
        'Longitude',
        'Priority',
        'ImageUrl',
        'IsActive',
        'Address',
        'FLICNo',
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
            if (empty($model->RestaurantId)) {
                $model->RestaurantId = (string) \Illuminate\Support\Str::uuid();
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

    public function foods()
    {
        return $this->hasMany(Food::class, 'RestaurantId', 'RestaurantId');
    }
}
