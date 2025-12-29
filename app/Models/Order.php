<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    // Specify custom table name
    protected $table = 'Orders';
    protected $primaryKey = 'OrderId';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'CustomerId',
        'OrderNumber',
        'Status',
        'TotalAmount',
        'RequiresPrescription',
        'PrescriptionImageUrl',
        'DeliveryAddress',
        'SpecialInstructions',
    ];
    

    // public function restaurant() {
    //     return $this->belongsTo(Restaurant::class,'restaurant_id', 'RestaurantId');
    // }

    // public function medicalstore() {
    //     return $this->belongsTo(MedicalStore::class, 'medicalstore_id', 'MedicalStoreId');
    // }

    // Order.php
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'OrderId', 'OrderId'); //First is related model(OrderItem), second is foreign key in OrderItem, third is local key in Order
    }

    // Relationship with Customer
    public function customer() {
        return $this->belongsTo(Customer::class, 'CustomerId', 'CustomerId');
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
