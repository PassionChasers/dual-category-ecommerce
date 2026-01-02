<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    
class Setting extends Model
{
    protected $table = 'Settings';
    protected $primaryKey = 'Id';

    public $incrementing = true;   // IMPORTANT
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'AppName',
        'MetaTitle',
        'MetaDescription',
        'ContactEmail',
        'ContactPhone',
        'ContactAddress',
        'FacebookUrl',
        'TwitterUrl',
        'LinkedInUrl',
        'InstagramUrl',
        'AppLogo',
        'Favicon',
        'MaintenanceMode',
    ];
}
