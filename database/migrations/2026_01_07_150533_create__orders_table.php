<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Orders', function (Blueprint $table) {
            $table->uuid('OrderId')->primary();
            $table->uuid('CustomerId');
            $table->string('OrderNumber', 50)->unique();
            $table->string('Status', 30)->default('Pending');
            $table->decimal('TotalAmount', 10, 2);
            $table->string('OrderDescription', 2000)->nullable();
            $table->string('CancellationReason', 1000)->nullable();
            $table->string('DeliveryAddress', 500);
            $table->double('Latitude')->nullable();
            $table->double('Longitude')->nullable();
            $table->string('SpecialInstructions', 1000)->nullable();
            $table->timestampsTz(); // Creates CreatedAt and UpdatedAt with timezone
            $table->timestampTz('ConfirmedAt')->nullable();
            $table->timestampTz('CompletedAt')->nullable();
            $table->timestampTz('CancelledAt')->nullable();
            $table->boolean('RequiresPrescription')->default(false);
            $table->string('PrescriptionImageUrl', 500)->nullable();

            // Foreign key
            $table->foreign('CustomerId')
                  ->references('CustomerId')
                  ->on('Customers')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('CreatedAt', 'IX_Orders_CreatedAt');
            $table->index('CustomerId', 'IX_Orders_CustomerId');
            $table->index('Status', 'IX_Orders_Status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Orders');
    }
};
