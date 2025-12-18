<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineCategory extends Model
{
    // use SoftDeletes;

    protected $table = 'MedicineCategories';

    // Non-standard primary key
    protected $primaryKey = 'MedicineCategoryId';
    // public $incrementing = true;
    // protected $keyType = 'int';
    // UUID is non-incrementing string
    public $incrementing = false;
    protected $keyType = 'string';


    // Use custom created column name
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null; // we don't have UpdatedAt by default

    protected $fillable = [
        'MedicineCategoryId', 
        'Name',
        'Description',
        'IsActive',
    ];

    protected $casts = [
        'IsActive' => 'boolean',
        'CreatedAt' => 'datetime',
    ];
}
