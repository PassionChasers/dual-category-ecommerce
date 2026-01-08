<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('InvoiceItems', function (Blueprint $table) {
            $table->uuid('InvoiceItemId')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('InvoiceId');
            $table->uuid('OrderItemId');
            $table->uuid('ProductId');
            $table->string('ProductName', 200);
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('TotalPrice', 10, 2);

            // Foreign keys
            $table->foreign('InvoiceId')
                  ->references('InvoiceId')
                  ->on('Invoices')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            $table->foreign('OrderItemId')
                  ->references('OrderItemId')
                  ->on('OrderItems')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('InvoiceId', 'IX_InvoiceItems_InvoiceId');
            $table->unique('OrderItemId', 'IX_InvoiceItems_OrderItemId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('InvoiceItems');
    }
};
