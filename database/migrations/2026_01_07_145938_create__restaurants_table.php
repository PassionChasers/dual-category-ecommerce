<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Restaurants', function (Blueprint $table) {
            $table->uuid('RestaurantId')->primary();
            $table->uuid('UserId');
            $table->string('Name', 150);
            $table->string('Slug', 160);
            $table->text('Address');
            $table->string('FLICNo', 20);
            $table->string('GSTIN', 15);
            $table->text('PAN');
            $table->boolean('IsPureVeg');
            $table->string('CuisineType', 100);
            $table->time('OpenTime');
            $table->time('CloseTime');
            $table->integer('PrepTimeMin');
            $table->decimal('DeliveryFee', 6, 2);
            $table->decimal('MinOrder', 8, 2);
            $table->integer('Priority')->default(1);
            $table->double('Latitude');
            $table->double('Longitude');
            $table->timestampTz('CreatedAt');
            $table->boolean('IsActive');

            // Foreign key
            $table->foreign('UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            // Unique index on UserId
            $table->unique('UserId', 'IX_Restaurants_UserId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Restaurants');
    }
};
