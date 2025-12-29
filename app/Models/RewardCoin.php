<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardCoin extends Model
{
    // Table name with capital first letter
    protected $table = 'RewardCoins';

    // Primary key is UUID
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;

    // Add fillable list as migration defines (placeholder until confirmed)
    protected $fillable = [];
}

