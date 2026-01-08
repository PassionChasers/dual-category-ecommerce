<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('OrderItems', function (Blueprint $table) {
            $table->uuid('OrderItemId')->primary();
            $table->uuid('OrderId');
            $table->uuid('MedicineId')->nullable();
            $table->uuid('MenuItemId')->nullable();
            $table->string('ItemType', 20);
            $table->decimal('UnitPriceAtOrder', 10, 2);
            $table->integer('Quantity')->default(1);
            $table->uuid('BusinessId')->nullable();
            $table->string('BusinessType', 20)->nullable();
            $table->timestampTz('ForwardedAt')->nullable();
            $table->uuid('AssignedByAdmin')->nullable();
            $table->string('Status', 30)->default('Pending');
            $table->timestampTz('AcceptedAt')->nullable();
            $table->timestampTz('ReadyAt')->nullable();
            $table->timestampTz('CompletedAt')->nullable();
            $table->timestampTz('RejectedAt')->nullable();
            $table->string('RejectionReason', 500)->nullable();
            $table->string('BusinessNotes', 1000)->nullable();
            $table->boolean('IsConsultationItem')->default(false);
            $table->string('ConsultationNotes', 1000)->nullable();
            $table->integer('ForwardCount')->default(0);
            $table->timestampsTz(); // CreatedAt and UpdatedAt
            $table->uuid('ItemId');

            // Foreign keys
            $table->foreign('MedicineId')->references('MedicineId')->on('Medicines')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('MenuItemId')->references('MenuItemId')->on('MenuItems')->onUpdate('no action')->onDelete('restrict');
            $table->foreign('OrderId')->references('OrderId')->on('Orders')->onUpdate('no action')->onDelete('cascade');

            // Indexes
            $table->index('BusinessId', 'IX_OrderItems_BusinessId');
            $table->index('BusinessType', 'IX_OrderItems_BusinessType');
            $table->index('ForwardedAt', 'IX_OrderItems_ForwardedAt');
            $table->index('MedicineId', 'IX_OrderItems_MedicineId');
            $table->index('MenuItemId', 'IX_OrderItems_MenuItemId');
            $table->index('OrderId', 'IX_OrderItems_OrderId');
            $table->index(['OrderId', 'ItemType'], 'IX_OrderItems_OrderId_ItemType');
            $table->index('Status', 'IX_OrderItems_Status');

            // Optional: Add check constraint using raw SQL for ItemType consistency
            $table->check("(
                (\"ItemType\" = 'Medicine' AND \"MedicineId\" IS NOT NULL AND \"MenuItemId\" IS NULL) OR
                (\"ItemType\" = 'Food' AND \"MenuItemId\" IS NOT NULL AND \"MedicineId\" IS NULL) OR
                (\"ItemType\" = 'Consultation' AND \"MedicineId\" IS NULL AND \"MenuItemId\" IS NULL AND \"IsConsultationItem\" = true)
            )");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('OrderItems');
    }
};
