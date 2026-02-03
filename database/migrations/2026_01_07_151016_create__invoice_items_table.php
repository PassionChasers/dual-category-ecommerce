<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('InvoiceItems', function (Blueprint $table) {

            // Primary key
            $table->uuid('InvoiceItemId')->primary()->default(DB::raw('gen_random_uuid()'));

            // Foreign keys
            $table->uuid('InvoiceId');
            $table->uuid('OrderItemId');
            $table->uuid('ProductId');

            // Columns
            $table->string('ProductName', 200);
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('TotalPrice', 10, 2);

            // Indexes
            $table->index('InvoiceId', 'IX_InvoiceItems_InvoiceId');
            $table->unique('OrderItemId', 'IX_InvoiceItems_OrderItemId');

            // Foreign key constraints
            $table->foreign(
                'InvoiceId',
                'FK_InvoiceItems_Invoices_InvoiceId'
            )
            ->references('InvoiceId')
            ->on('Invoices')
            ->onUpdate('no action')
            ->onDelete('cascade');

            $table->foreign(
                'OrderItemId',
                'FK_InvoiceItems_OrderItems_OrderItemId'
            )
            ->references('OrderItemId')
            ->on('OrderItems')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('InvoiceItems');
    }
};
