<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Medicines', function (Blueprint $table) {
            $table->uuid('MedicineId')->primary();
            $table->uuid('MedicineCategoryId');
            $table->string('Name', 200);
            $table->string('GenericName', 200)->nullable();
            $table->string('BrandName', 100)->nullable();
            $table->string('Description', 500)->nullable();
            $table->decimal('Price', 10, 2);
            $table->boolean('PrescriptionRequired');
            $table->string('Manufacturer', 200)->nullable();
            $table->date('ExpiryDate')->nullable();
            $table->string('DosageForm', 50)->nullable();
            $table->string('Strength', 50)->nullable();
            $table->string('Packaging', 100)->nullable();
            $table->decimal('AvgRating', 3, 2)->default(0.0);
            $table->integer('TotalReviews')->default(0);
            $table->string('ImageUrl', 500)->nullable();
            $table->boolean('IsActive');
            $table->timestampsTz(); // CreatedAt and UpdatedAt

            // Foreign key
            $table->foreign('MedicineCategoryId')
                  ->references('MedicineCategoryId')
                  ->on('MedicineCategories')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('IsActive', 'IX_Medicines_IsActive');
            $table->index('MedicineCategoryId', 'IX_Medicines_MedicineCategoryId');
            $table->index('Name', 'IX_Medicines_Name');
            $table->index('PrescriptionRequired', 'IX_Medicines_PrescriptionRequired');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Medicines');
    }
};
