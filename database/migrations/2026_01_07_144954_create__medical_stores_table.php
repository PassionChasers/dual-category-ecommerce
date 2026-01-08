<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MedicalStores', function (Blueprint $table) {
            $table->uuid('MedicalStoreId')->primary();
            $table->uuid('UserId');
            $table->string('Name', 150);
            $table->string('Slug', 160);
            $table->string('LicenseNumber', 50);
            $table->string('GSTIN', 15);
            $table->string('PAN', 10);
            $table->boolean('IsActive');
            $table->time('OpenTime');
            $table->time('CloseTime');
            $table->decimal('RadiusKm', 8, 2); // numeric in PostgreSQL, mapped to decimal
            $table->decimal('DeliveryFee', 6, 2);
            $table->decimal('MinOrder', 8, 2);
            $table->integer('Priority')->default(1);
            $table->text('Address');
            $table->double('Latitude');
            $table->double('Longitude');
            $table->timestampTz('CreatedAt');

            // Foreign key
            $table->foreign('UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            // Unique index
            $table->unique('UserId', 'IX_MedicalStores_UserId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MedicalStores');
    }
};
