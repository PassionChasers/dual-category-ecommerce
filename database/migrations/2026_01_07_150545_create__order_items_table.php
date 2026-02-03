<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('OrderItems', function (Blueprint $table) {

            // Primary key
            $table->uuid('OrderItemId')->primary();

            // Foreign keys
            $table->uuid('OrderId');
            $table->uuid('MedicineId')->nullable();
            $table->uuid('MenuItemId')->nullable();

            // Columns
            $table->string('ItemType', 20);
            $table->decimal('UnitPriceAtOrder', 10, 2);
            $table->integer('Quantity')->default(1);
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('CreatedAt', 'IX_OrderItems_CreatedAt');
            $table->index('ItemType', 'IX_OrderItems_ItemType');
            $table->index('MedicineId', 'IX_OrderItems_MedicineId');
            $table->index('MenuItemId', 'IX_OrderItems_MenuItemId');
            $table->index('OrderId', 'IX_OrderItems_OrderId');

            // Foreign key constraints
            $table->foreign(
                'OrderId',
                'FK_OrderItems_Orders_OrderId'
            )
            ->references('OrderId')
            ->on('Orders')
            ->onUpdate('no action')
            ->onDelete('cascade');

            $table->foreign(
                'MedicineId',
                'FK_OrderItems_Medicines_MedicineId'
            )
            ->references('MedicineId')
            ->on('Medicines')
            ->onUpdate('no action')
            ->onDelete('restrict');

            $table->foreign(
                'MenuItemId',
                'FK_OrderItems_MenuItems_MenuItemId'
            )
            ->references('MenuItemId')
            ->on('MenuItems')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('OrderItems');
    }
};
