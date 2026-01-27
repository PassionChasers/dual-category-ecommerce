<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    // Table name with capital first letter
    protected $table = 'Medicines';

    // Primary key is UUID string
    protected $primaryKey = 'MedicineId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (uses created_at & updated_at from migrations)
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // protected $fillable = [
    //     'MedicineCategoryId',
    //     'Name',
    //     'GenericName',
    //     'BrandName',
    //     'Description',
    //     'Price',
    //     'PrescriptionRequired',
    //     'Manufacturer',
    //     'ExpiryDate',
    //     'DosageForm',
    //     'Strength',
    //     'Packaging',
    //     'ImageUrl',
    //     'IsActive',
    //     'AvgRating',
    //     'TotalReviews',
    // ];

    protected $fillable = [
        'MedicineId',
        'MedicineCategoryId',
        'Name',
        'GenericName',
        'BrandName',
        'Description',
        'Price',
        'PrescriptionRequired',
        'Manufacturer',
        'ExpiryDate',
        'DosageForm',
        'Strength',
        'Packaging',
        'IsActive',
        'ImageUrl',
        'AvgRating',
        'TotalReviews',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'MRP' => 'decimal:2',
        'PrescriptionRequired' => 'boolean',
        'IsActive' => 'boolean',
        'CreatedAt' => 'datetime',
        'UpdatedAt' => 'datetime',
        'AvgRating' => 'float',
        'TotalReviews' => 'integer',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->MedicineId)) {
                $model->MedicineId = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\MedicineCategory::class, 'MedicineCategoryId', 'MedicineCategoryId');
    }

    public function store()
    {
        return $this->belongsTo(\App\Models\MedicalStore::class, 'MedicalStoreId', 'MedicalStoreId');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'MedicineId', 'MedicineId');
    }
}
