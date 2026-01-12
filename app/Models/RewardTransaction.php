<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardTransaction extends Model
{
    // Table name
    protected $table = 'RewardTransactions';

    // Primary key
    protected $primaryKey = 'RewardTransactionId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps
    public $timestamps = false;
    const CREATED_AT = 'TransactionDate';
    const UPDATED_AT = null;

    // Fillable attributes
    protected $fillable = [
        'RewardTransactionId',
        'CustomerId',
        'OrderId',
        'Type',
        'Amount',
        'Description',
        'Status',
        'TransactionDate',
        'ReferenceId',
        'Notes',
    ];

    // Casts
    protected $casts = [
        'Amount' => 'decimal:2',
        'TransactionDate' => 'datetime',
    ];

    /**
     * Relationship: RewardTransaction belongs to Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerId', 'CustomerId');
    }

    /**
     * Relationship: RewardTransaction belongs to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderId', 'OrderId');
    }
}
