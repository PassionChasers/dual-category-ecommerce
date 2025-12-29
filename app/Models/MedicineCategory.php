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

    // Timestamps enabled (migrations use timestamps())
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
