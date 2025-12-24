<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;

    protected $table = 'foodcategories'; // your table name

    protected $primaryKey = 'FoodCategoryId'; // UUID primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Name',
        'Description',
        'IsActive',
    ];

    // Timestamps
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // Relationship: FoodCategory has many Foods
    public function foods()
    {
        return $this->hasMany(Food::class, 'FoodCategoryId', 'FoodCategoryId');
    }
}
