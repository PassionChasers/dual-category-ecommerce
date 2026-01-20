<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('OrderRejections', function (Blueprint $table) {

            // Primary Key
            $table->uuid('OrderRejectionId')->primary();

            // Foreign & related columns
            $table->uuid('OrderId');
            $table->uuid('BusinessId');
            $table->string('BusinessType', 20);
            $table->string('RejectionReason', 500)->nullable();
            $table->timestampTz('RejectedAt');

            // Foreign Key Constraint
            $table->foreign('OrderId', 'FK_OrderRejections_Orders_OrderId')
                ->references('OrderId')
                ->on('Orders')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // Indexes
            $table->index('BusinessId', 'IX_OrderRejections_BusinessId');
            $table->index('BusinessType', 'IX_OrderRejections_BusinessType');
            $table->index('OrderId', 'IX_OrderRejections_OrderId');
            $table->index('RejectedAt', 'IX_OrderRejections_RejectedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('OrderRejections');
    }
};
