<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('MenuCategories', function (Blueprint $table) {
            $table->uuid('MenuCategoryId')->primary();
            $table->string('Name', 100);
            $table->text('Description');
            $table->boolean('IsActive');
            $table->timestampTz('CreatedAt');

            // Unique index on Name
            $table->unique('Name', 'IX_MenuCategories_Name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('MenuCategories');
    }
};
