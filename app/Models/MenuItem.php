<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    // Table name with capital first letter
    protected $table = 'MenuItems';

    protected $primaryKey = 'MenuItemId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'MenuCategoryId',
        'Name',
        'Description',
        'Price',
        'IsVeg',
        'IsAvailable',
        'PreparationTimeMin',
        'ImageUrl',
        'AvgRating',
        'TotalReviews',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'MenuCategoryId', 'MenuCategoryId');
    }
}
