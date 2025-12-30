<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'MenuItems';

    protected $primaryKey = 'FoodId'; // UUID primary key
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
    ];

    // Timestamps
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

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
