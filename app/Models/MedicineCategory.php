<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MedicineCategory extends Model
{
    // Table name with capital first letter
    protected $table = 'MedicineCategories';

    protected $primaryKey = 'MedicineCategoryId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Custom timestamp column names - table only has CreatedAt
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;  // Table doesn't have updated_at column

    protected $fillable = [
        'Name',
        'Description',
        'IsActive',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->MedicineCategoryId)) {
                $model->MedicineCategoryId = (string) Str::uuid();
            }
        });
    }
}
