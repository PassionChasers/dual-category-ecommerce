<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MedicineCategory extends Model
{
    protected $table = 'MedicineCategories';
    protected $primaryKey = 'MedicineCategoryId';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Description',
        'IsActive',
        'CreatedAt',
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
