<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuItem;

class MenuCategory extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'MenuCategories';

    protected $primaryKey = 'MenuCategoryId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Custom timestamp column names
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;  // Table doesn't have UpdatedAt column

    protected $fillable = [
        'MenuCategoryId',
        'Name',
        'Description',
        'IsActive',
        'ImageUrl',
    ];

    // Relationships
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'MenuCategoryId', 'MenuCategoryId');
    }
}
