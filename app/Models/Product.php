<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; // Name of your existing table
    protected $primaryKey = 'id';  // Primary key column
    public $timestamps = false;   // Disable timestamps if not used
}
