<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;

    // Table name with capital first letter
    protected $table = 'MenuCategories';

    protected $primaryKey = 'MenuCategoryId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'Name',
        'Description',
        'IsActive',
    ];

    // Relationships
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'MenuCategoryId', 'MenuCategoryId');
    }
}
