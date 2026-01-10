<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'AuditLogs';

    protected $primaryKey = 'Id';  

    protected $fillable = [
        'UserId',
        'Action',
        'AuditableType',
        'AuditableId',
        'OldValues',
        'NewValues',
        'IpAddress',
        'Location',
    ];

    protected $casts = [
        'OldValues' => 'array',
        'NewValues' => 'array',
    ];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'UserId');
    }
}

