<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'user_id',
        'order_type',
        'restaurant_id',
        'medicalstore_id',
        'order_number',
        'subtotal',
        'delivery_charge',
        'tax',
        'discount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'delivery_address',
        'notes',
        'prescription_image',
        'prescription_verified'
    ];

    // Order.php
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function restaurant() {
        return $this->belongsTo(Restaurant::class,'restaurant_id', 'RestaurantId');
    }

    public function medicalstore() {
        return $this->belongsTo(MedicalStore::class, 'medicalstore_id', 'MedicalStoreId');
    }

    // Order.php
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
