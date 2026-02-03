<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Invoices', function (Blueprint $table) {

            // Primary key
            $table->uuid('InvoiceId')->primary()->default(DB::raw('gen_random_uuid()'));

            // Foreign keys
            $table->uuid('OrderId');
            $table->uuid('CustomerId');

            // Columns
            $table->string('InvoiceNumber', 50);
            $table->decimal('SubTotal', 10, 2);
            $table->decimal('Tax', 10, 2)->default(0.0);
            $table->decimal('DeliveryFee', 10, 2);
            $table->decimal('TotalAmount', 10, 2);
            $table->integer('PaymentMethod');
            $table->integer('Status');
            $table->timestampTz('IssuedAt')->nullable();
            $table->timestampTz('CancelledAt')->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('CreatedAt', 'IX_Invoices_CreatedAt');
            $table->index('CustomerId', 'IX_Invoices_CustomerId');
            $table->unique('InvoiceNumber', 'IX_Invoices_InvoiceNumber');
            $table->unique('OrderId', 'IX_Invoices_OrderId');
            $table->index('Status', 'IX_Invoices_Status');

            // Foreign key constraints
            $table->foreign(
                'CustomerId',
                'FK_Invoices_Customers_CustomerId'
            )
            ->references('CustomerId')
            ->on('Customers')
            ->onUpdate('no action')
            ->onDelete('restrict');

            $table->foreign(
                'OrderId',
                'FK_Invoices_Orders_OrderId'
            )
            ->references('OrderId')
            ->on('Orders')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Invoices');
    }
};
