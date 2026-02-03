<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Orders', function (Blueprint $table) {

            // Primary key
            $table->uuid('OrderId')->primary();

            // Foreign keys
            $table->uuid('CustomerId');
            $table->uuid('DeliveryManId')->nullable();

            // Core order fields
            $table->string('OrderNumber', 50);
            $table->integer('Status')->default(1);
            $table->decimal('TotalAmount', 10, 2);

            // Business info
            $table->uuid('BusinessId')->nullable();
            $table->string('BusinessType', 20)->nullable();

            // Prescription
            $table->boolean('RequiresPrescription')->default(false);
            $table->string('PrescriptionImageUrl', 500)->nullable();

            // Descriptions & notes
            $table->string('OrderDescription', 2000)->nullable();
            $table->string('BusinessNotes', 1000)->nullable();
            $table->string('CancellationReason', 1000)->nullable();
            $table->string('SpecialInstructions', 1000)->nullable();

            // Address & location
            $table->string('DeliveryAddress', 500);
            $table->double('Latitude')->nullable();
            $table->double('Longitude')->nullable();

            // Status timestamps
            $table->timestampTz('AcceptedAt')->nullable();
            $table->timestampTz('PreparingAt')->nullable();
            $table->timestampTz('PackedAt')->nullable();
            $table->timestampTz('ShippingAt')->nullable();
            $table->timestampTz('CompletedAt')->nullable();
            $table->timestampTz('CancelledAt')->nullable();

            // Created
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('BusinessId', 'IX_Orders_BusinessId');
            $table->index('BusinessType', 'IX_Orders_BusinessType');
            $table->index('CreatedAt', 'IX_Orders_CreatedAt');
            $table->index('CustomerId', 'IX_Orders_CustomerId');
            $table->index('DeliveryManId', 'IX_Orders_DeliveryManId');
            $table->unique('OrderNumber', 'IX_Orders_OrderNumber');
            $table->index('Status', 'IX_Orders_Status');

            // Foreign key constraints
            $table->foreign(
                'CustomerId',
                'FK_Orders_Customers_CustomerId'
            )
            ->references('CustomerId')
            ->on('Customers')
            ->onUpdate('no action')
            ->onDelete('restrict');

            $table->foreign(
                'DeliveryManId',
                'FK_Orders_DeliveryMen_DeliveryManId'
            )
            ->references('DeliveryManId')
            ->on('DeliveryMen')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Orders');
    }
};
