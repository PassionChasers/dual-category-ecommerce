<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    // Table name with capital first letter
    protected $table = 'Ads';

    // Primary key is UUID
    protected $primaryKey = 'AdId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (use custom column names)
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // All user-assignable columns (exclude primary key and timestamps)
    protected $fillable = [
        'Title',
        'ImageUrl',
        'RedirectUrl',
        'AdvertiserName',
        'Description',
        'IsActive',
        'StartDate',
        'EndDate',
        'CostPerClick',
        'CostPerThausandImpressions',
        'TotalBudget',
        'TotalImpressions',
        'TotalClicks',
        'AmountSpent',
    ];
}
