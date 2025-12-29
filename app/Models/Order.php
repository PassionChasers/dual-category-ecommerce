<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class Order extends Model
{
    // Table name with standard snake_case
    protected $table = 'orders';

    // Primary key is UUID
    protected $primaryKey = 'OrderId';
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps enabled
    public $timestamps = true;
      const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'CustomerId',
        'OrderNumber',
        'Status',
        'TotalAmount',
        'RequiresPrescription',
        'PrescriptionImageUrl',
        'DeliveryAddress',
        'SpecialInstructions',
        'Latitude',
        'Longitude',
        'CompletedAt',
        'CancelledAt',
        'ConfirmedAt',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'RestaurantId');
    }

    public function medicalstore()
    {
        return $this->belongsTo(MedicalStore::class, 'medicalstore_id', 'MedicalStoreId');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    /**
     * Automatically delete order items when order is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($order) {
            $order->items()->delete();
        });
    }
}
