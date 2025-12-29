<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Standard snake_case table name
    protected $table = 'products';

    // Primary key is int from migrations
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps enabled
    public $timestamps = true;

    protected $fillable = [];
}

