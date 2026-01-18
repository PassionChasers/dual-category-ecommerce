<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class Order extends Model
{

    // Specify custom table name
    protected $table = 'Orders';

    // Primary key is UUID
    protected $primaryKey = 'OrderId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    const STATUS_ACCEPTED = 4;
    const STATUS_REJECTED = 5;
    const STATUS_CANCELLED = 9;

    protected $fillable = [
        'CustomerId',
        'OrderNumber',
        'Status',
        'TotalAmount',
        'BusinessId',
        'BusinessType',
        'RequiresPrescription',
        'PrescriptionImageUrl',
        'OrderDescription',
        'AcceptedAt',
        'PreparingAt',
        'PackedAt',
        'ShippingAt',
        'BusinessNotes',
        'CancellationReason',
        'DeliveryAddress',
        'Latitude',
        'Longitude',
        'SpecialInstructions',
        'CreatedAt',
        'CompletedAt',
        'CancelledAt',
        'DeliveryManId',
    ];

    protected $casts = [
        'CreatedAt'    => 'datetime',
        'CancelledAt'  => 'datetime',
        'CompletedAt'  => 'datetime',
        'AcceptedAt'   => 'datetime',
        'PreparingAt'  => 'datetime',
        'PackedAt'     => 'datetime',
        'ShippingAt'   => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->OrderId)) {
                $model->OrderId = (string) Str::uuid();
            }
        });
    }


    public function deliveryMan() {
        return $this->belongsTo(DeliveryMan::class, 'DeliveryManId', 'DeliveryManId');
    }

    // Relationship with Customer
    public function customer() {
        return $this->belongsTo(Customer::class, 'CustomerId', 'CustomerId');
    }

    /**
     * Automatically delete order items when order is deleted
     */
    // protected static function booted()
    // {
    //     static::deleting(function ($order) {
    //         $order->items()->delete();
    //     });
    // }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'OrderId', 'OrderId'); //First is related model(OrderItem), second is foreign key in OrderItem, third is local key in Order
    }

    public function rejection()
    {
        return $this->hasMany(OrderRejection::class, 'OrderId', 'OrderId'); //First is related model(OrderItem), second is foreign key in OrderItem, third is local key in Order
    }


    /****************
     * Bellow all are usable Query Scopes (MOST IMPORTANT) to reuse in Controller
     ****************/
    public function scopeFilterStatus($query, $status)
    {
        if ($status) {
            $query->where('Status', $status);
        }
    }

    public function scopeSort($query, $sortBy, $sortOrder)
    {
        $allowed = ['CreatedAt', 'TotalAmount'];

        $query->orderBy(
            in_array($sortBy, $allowed) ? $sortBy : 'CreatedAt',
            $sortOrder === 'asc' ? 'asc' : 'desc'
        );
    }

    public function scopeSearchMedicine($query, $search)
    {
        if (!$search) return;

        $query->whereHas('items', function ($q) use ($search) {
            $q->whereHas('medicine', function ($m) use ($search) {
                $m->where('Name', 'ILIKE', "%{$search}%");
            });
        });
    }

    public function scopeSearchFood($query, $search)
    {
        if (!$search) return;

        $query->whereHas('items', function ($q) use ($search) {
            $q->whereHas('food', function ($f) use ($search) {
                $f->where('Name', 'ILIKE', "%{$search}%");
            });
        });
    }
}
