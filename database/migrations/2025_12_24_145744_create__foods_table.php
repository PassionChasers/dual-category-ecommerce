<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Foods', function (Blueprint $table) {
            $table->uuid('FoodId')->primary();
            $table->uuid('RestaurantId');
            $table->uuid('FoodCategoryId');

            $table->string('Name');
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->decimal('MRP', 10, 2)->nullable();
            $table->string('ImageUrl')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->decimal('AvgRating', 3, 1)->default(0);
            $table->unsignedInteger('TotalReviews')->default(0);

            $table->timestamps(); // created_at, updated_at

            // Foreign keys
            $table->foreign('RestaurantId')->references('RestaurantId')->on('restaurants')->onDelete('cascade');
            $table->foreign('FoodCategoryId')->references('FoodCategoryId')->on('FoodCategories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Foods');
    }
};
