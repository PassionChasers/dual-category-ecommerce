<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('medicalstores', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('MedicalStoreId')->primary();

            // owner / relation
            $table->uuid('UserId')->nullable();

            $table->string('Name', 191);
            $table->string('Slug', 191)->nullable()->index();
            $table->string('LicenseNumber', 100)->nullable();
            $table->string('GSTIN', 50)->nullable();
            $table->string('PAN', 50)->nullable();

            $table->boolean('IsActive')->default(true);
            $table->boolean('IsFeatured')->default(false);

            $table->time('OpenTime')->nullable();
            $table->time('CloseTime')->nullable();

            $table->decimal('RadiusKm', 6, 2)->nullable();
            $table->decimal('DeliveryFee', 10, 2)->nullable();
            $table->decimal('MinOrder', 10, 2)->nullable();

            $table->decimal('Latitude', 10, 7)->nullable();
            $table->decimal('Longitude', 10, 7)->nullable();

            $table->integer('Priority')->default(0);

            // optional image
            $table->string('ImageUrl')->nullable();

            // keep CreatedAt to match your schemas; also Laravel timestamps
            $table->timestamp('CreatedAt')->nullable();
            $table->timestamps(); // created_at & updated_at for Laravel conveniences
        });
    }

    public function down()
    {
        Schema::dropIfExists('MedicalStores');
    }
};
