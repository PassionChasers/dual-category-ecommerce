<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;

    // Table name with capital first letter
    protected $table = 'FoodCategories';

    protected $primaryKey = 'FoodCategoryId'; // UUID primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'Name',
        'Description',
        'IsActive',
    ];

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relationship: FoodCategory has many Foods
    public function foods()
    {
        return $this->hasMany(Food::class, 'FoodCategoryId', 'FoodCategoryId');
    }
}
