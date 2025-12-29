<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // Table name with capital first letter
    protected $table = 'Invoices';

    // Primary key is UUID
    protected $primaryKey = 'InvoiceId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled (use custom column names)
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // All user-assignable columns (exclude primary key and timestamps)
    protected $fillable = [
        'OrderId',
        'InvoiceNumber',
        'CustomerId',
        'SubTotal',
        'Tax',
        'DeliveryFee',
        'TotalAmount',
        'PaymentMethod',
        'Status',
        'IssuedAt',
        'CancelledAt',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderId', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerId', 'CustomerId');
    }
}

