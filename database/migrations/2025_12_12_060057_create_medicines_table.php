<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinesTable extends Migration
{
    public function up(): void
    {
        Schema::create('Medicines', function (Blueprint $table) {
            $table->bigIncrements('MedicineId');
            $table->unsignedBigInteger('MedicalStoreId')->nullable()->index();
            // MedicineCategoryId type chosen as unsignedBigInteger for compatibility with bigIncrements.
            // If your categories use UUIDs change this to ->string('MedicineCategoryId',36)->nullable()->index();
            $table->unsignedBigInteger('MedicineCategoryId')->nullable()->index();

            $table->string('Name', 191);
            $table->string('GenericName', 191)->nullable();
            $table->string('BrandName', 191)->nullable();
            $table->text('Description')->nullable();

            $table->decimal('Price', 12, 2)->default(0.00);
            $table->decimal('MRP', 12, 2)->nullable();
            $table->boolean('PrescriptionRequired')->default(false);

            $table->string('Manufacturer', 191)->nullable();
            $table->date('ExpiryDate')->nullable();

            $table->string('DosageForm', 100)->nullable();
            $table->string('Strength', 100)->nullable();
            $table->string('Packaging', 100)->nullable();

            $table->string('ImageUrl', 255)->nullable();

            $table->boolean('IsActive')->default(true);

            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->nullable()->useCurrentOnUpdate();

            $table->decimal('AvgRating', 3, 2)->default(0.00);
            $table->unsignedInteger('TotalReviews')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Medicines');
    }
}
