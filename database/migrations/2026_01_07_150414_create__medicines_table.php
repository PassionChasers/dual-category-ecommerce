<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Medicines', function (Blueprint $table) {

            // Primary key
            $table->uuid('MedicineId')->primary();

            // Foreign key
            $table->uuid('MedicineCategoryId');

            // Columns
            $table->string('Name', 200);
            $table->string('GenericName', 200)->nullable();
            $table->string('BrandName', 100)->nullable();
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->boolean('PrescriptionRequired');
            $table->string('Manufacturer', 200)->nullable();
            $table->date('ExpiryDate')->nullable();
            $table->string('DosageForm', 50)->nullable();
            $table->string('Strength', 50)->nullable();
            $table->string('Packaging', 100)->nullable();
            $table->decimal('AvgRating', 3, 2)->default(0.0);
            $table->integer('TotalReviews')->default(0);
            $table->text('ImageUrl')->nullable();
            $table->boolean('IsActive');
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('UpdatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('IsActive', 'IX_Medicines_IsActive');
            $table->index('MedicineCategoryId', 'IX_Medicines_MedicineCategoryId');
            $table->index('Name', 'IX_Medicines_Name');
            $table->index('PrescriptionRequired', 'IX_Medicines_PrescriptionRequired');

            // Foreign key constraint
            $table->foreign(
                'MedicineCategoryId',
                'FK_Medicines_MedicineCategories_MedicineCategoryId'
            )
            ->references('MedicineCategoryId')
            ->on('MedicineCategories')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Medicines');
    }
};
