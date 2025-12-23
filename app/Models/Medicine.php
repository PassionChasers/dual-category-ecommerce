<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $table = 'Medicines';

    // Primary key is UUID string
    protected $primaryKey = 'MedicineId';
    public $incrementing = false;      // important: not auto-incrementing
    protected $keyType = 'string';     // store as string

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'MedicineId',
        'MedicalStoreId',
        'MedicineCategoryId',
        'Name',
        'GenericName',
        'BrandName',
        'Description',
        'Price',
        'MRP',
        'PrescriptionRequired',
        'Manufacturer',
        'ExpiryDate',
        'DosageForm',
        'Strength',
        'Packaging',
        'ImageUrl',
        'IsActive',
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
                $model->MedicineId = (string) Str::uuid();
            }
        });
    }

    
    // optional relations (no foreign key forced)
    public function category()
    {
        return $this->belongsTo(\App\Models\MedicineCategory::class, 'MedicineCategoryId', 'MedicineCategoryId');
    }

    public function store()
    {
        return $this->belongsTo(\App\Models\MedicalStore::class, 'MedicalStoreId', 'MedicalStoreId');
    }

    // protected static function booted()
    // {
    //     static::creating(function ($model) {
    //         if (empty($model->MedicineId)) {
    //             $model->MedicineId = (string) Str::uuid();
    //         }
    //     });
    // }
    
}
