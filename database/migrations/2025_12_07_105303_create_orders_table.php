<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            // Primary Key
            $table->uuid('id')->primary();

            // Who placed the order (customer)
            $table->uuid('user_id');

            // Order type: food or medicine
            $table->enum('order_type', ['food', 'medicine']);

            // Store references (nullable based on type)
            $table->uuid('restaurant_id')->nullable();
            $table->uuid('medicalstore_id')->nullable();

            // Order details
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);

            // Payment
            $table->enum('payment_method', ['cod', 'khalti', 'esewa', 'card']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

            // Order status
            $table->enum('order_status', [
                'pending',
                'accepted',
                'preparing',
                'packed',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending');

            // Address & notes
            $table->text('delivery_address');
            $table->text('notes')->nullable();

            // Prescription (for medicine orders)
            $table->string('prescription_image')->nullable();
            $table->boolean('prescription_verified')->default(false);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('restaurant_id')->references('RestaurantId')->on('restaurants')->nullOnDelete();
            $table->foreign('medicalstore_id')->references('MedicalStoreId')->on('medicalstores')->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
