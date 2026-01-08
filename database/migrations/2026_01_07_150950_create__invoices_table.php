<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Invoices', function (Blueprint $table) {
            $table->uuid('InvoiceId')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('OrderId');
            $table->string('InvoiceNumber', 50)->unique();
            $table->uuid('CustomerId');
            $table->decimal('SubTotal', 10, 2);
            $table->decimal('Tax', 10, 2)->default(0.0);
            $table->decimal('DeliveryFee', 10, 2);
            $table->decimal('TotalAmount', 10, 2);
            $table->text('PaymentMethod');
            $table->text('Status');
            $table->timestampTz('IssuedAt')->nullable();
            $table->timestampTz('CancelledAt')->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Foreign keys
            $table->foreign('CustomerId')
                  ->references('CustomerId')
                  ->on('Customers')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            $table->foreign('OrderId')
                  ->references('OrderId')
                  ->on('Orders')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('CreatedAt', 'IX_Invoices_CreatedAt');
            $table->index('CustomerId', 'IX_Invoices_CustomerId');
            $table->index('Status', 'IX_Invoices_Status');
            $table->unique('InvoiceNumber', 'IX_Invoices_InvoiceNumber');
            $table->unique('OrderId', 'IX_Invoices_OrderId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Invoices');
    }
};
