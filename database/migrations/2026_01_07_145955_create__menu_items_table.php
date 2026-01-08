<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MenuItems', function (Blueprint $table) {
            $table->uuid('MenuItemId')->primary();
            $table->uuid('MenuCategoryId');
            $table->string('Name', 200);
            $table->string('Description', 500)->nullable();
            $table->decimal('Price', 10, 2);
            $table->boolean('IsVeg');
            $table->boolean('IsAvailable');
            $table->integer('PreparationTimeMin')->default(15);
            $table->decimal('AvgRating', 3, 2)->default(0.0);
            $table->integer('TotalReviews')->default(0);
            $table->string('ImageUrl', 500)->nullable();
            $table->timestampsTz(); // CreatedAt and UpdatedAt

            // Foreign key
            $table->foreign('MenuCategoryId')
                  ->references('MenuCategoryId')
                  ->on('MenuCategories')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('IsAvailable', 'IX_MenuItems_IsAvailable');
            $table->index('IsVeg', 'IX_MenuItems_IsVeg');
            $table->index('MenuCategoryId', 'IX_MenuItems_MenuCategoryId');
            $table->index('Name', 'IX_MenuItems_Name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MenuItems');
    }
};
