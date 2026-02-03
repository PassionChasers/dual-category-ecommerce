<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MedicalStores', function (Blueprint $table) {

            // Primary key
            $table->uuid('MedicalStoreId')->primary();

            // Foreign key
            $table->uuid('UserId');

            // Columns
            $table->string('Name', 150);
            $table->string('Slug', 160);
            $table->string('LicenseNumber', 50);
            $table->string('GSTIN', 15);
            $table->string('PAN', 10);
            $table->boolean('IsActive');
            $table->time('OpenTime');
            $table->time('CloseTime');
            $table->decimal('RadiusKm');          // numeric
            $table->decimal('DeliveryFee', 6, 2);
            $table->decimal('MinOrder', 8, 2);
            $table->integer('Priority')->default(1);
            $table->text('Address');
            $table->double('Latitude');
            $table->double('Longitude');
            $table->timestampTz('CreatedAt');

            // Indexes
            $table->unique(
                'UserId',
                'IX_MedicalStores_UserId'
            );

            // Foreign key constraints
            $table->foreign('UserId', 'FK_MedicalStores_Users_UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MedicalStores');
    }
};
