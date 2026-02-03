<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MenuItems', function (Blueprint $table) {

            // Primary key
            $table->uuid('MenuItemId')->primary();

            // Foreign key
            $table->uuid('MenuCategoryId');

            // Columns
            $table->string('Name', 200);
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->boolean('IsVeg');
            $table->boolean('IsAvailable');
            $table->integer('PreparationTimeMin')->default(15);
            $table->decimal('AvgRating', 3, 2)->default(0.0);
            $table->integer('TotalReviews')->default(0);
            $table->text('ImageUrl')->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('UpdatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('IsAvailable', 'IX_MenuItems_IsAvailable');
            $table->index('IsVeg', 'IX_MenuItems_IsVeg');
            $table->index('MenuCategoryId', 'IX_MenuItems_MenuCategoryId');
            $table->index('Name', 'IX_MenuItems_Name');

            // Foreign key constraints
            $table->foreign(
                'MenuCategoryId',
                'FK_MenuItems_MenuCategories_MenuCategoryId'
            )
            ->references('MenuCategoryId')
            ->on('MenuCategories')
            ->onUpdate('no action')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MenuItems');
    }
};
