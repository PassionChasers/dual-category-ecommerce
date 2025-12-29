<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    // Table name with capital first letter
    protected $table = 'Foods';

    protected $primaryKey = 'FoodId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'RestaurantId',
        'FoodCategoryId',
        'Name',
        'Description',
        'Price',
        'MRP',
        'ImageUrl',
        'IsActive',
        'AvgRating',
        'TotalReviews',
    ];

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relationships
    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'FoodCategoryId', 'FoodCategoryId');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'RestaurantId', 'RestaurantId');
    }
}
