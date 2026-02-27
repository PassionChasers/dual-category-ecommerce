<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class MedicalStore extends Model
{
    use Encryptable;

    // Table name with capital first letter
    protected $table = 'MedicalStores';

    // Primary key is UUID non-incrementing
    protected $primaryKey = 'MedicalStoreId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (uses created_at & updated_at)
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

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
        'Address',
        'Latitude',
        'Longitude',
        'Priority',
    ];

    protected $encrypted = [
        'LicenseNumber',
        'GSTIN',
        'PAN',
    ];

    // Casts
    // protected $casts = [
    //     'IsActive' => 'boolean',
    //     'IsFeatured' => 'boolean',
    //     'OpenTime' => 'string',
    //     'CloseTime' => 'string',
    //     'RadiusKm' => 'decimal:2',
    //     'DeliveryFee' => 'decimal:2',
    //     'MinOrder' => 'decimal:2',
    //     'Latitude' => 'decimal:7',
    //     'Longitude' => 'decimal:7',
    //     'Priority' => 'integer',
    // ];

    protected $casts = [
        'IsActive'    => 'boolean',
        'IsFeatured'  => 'boolean',
        'OpenTime'    => 'datetime:H:i',   // if storing TIME, otherwise 'string' is okay
        'CloseTime'   => 'datetime:H:i',   // same as above
        'RadiusKm'    => 'decimal:2',
        'DeliveryFee' => 'decimal:2',
        'MinOrder'    => 'decimal:2',
        'Latitude'    => 'decimal:7',
        'Longitude'   => 'decimal:7',
        'Priority'    => 'integer',
    ];


    // Accessors for encrypted fields
    
    public function getLicenseNumberAttribute($value)
    {
        return $this->decryptAttribute($value);
    }

    public function getGstinAttribute($value)
    {
        return $this->decryptAttribute($value);
    }

    public function getPanAttribute($value)
    {
        return $this->decryptAttribute($value);
    }



    /*
    |--------------------------------------------------------------------------
    | Safe Decryption Handler (PostgreSQL bytea Compatible)
    |--------------------------------------------------------------------------
    */

    protected function decryptSafe($value)
    {
        if (!$value) {
            return null;
        }

        try {

            if (is_resource($value)) {
                $value = stream_get_contents($value);
            }

            if (!is_string($value)) {
                return null;
            }

            return app(\App\Services\AesEncryptionService::class)
                ->decrypt($value);

        } catch (\Throwable $e) {
            return null;
        }
    }

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

    public function rejectedOrder()
    {
        return $this->hasMany(OrderRejection::class, 'BusinessId', 'MedicalStoreId'); 
    }

}
