<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('MedicineCategories', function (Blueprint $table) {
            $table->bigIncrements('MedicineCategoryId'); // primary key as in your screenshot
            $table->string('Name', 191);
            $table->text('Description')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamp('CreatedAt')->useCurrent();
            // $table->softDeletes(); // adds deleted_at
            // Note: no updated_at column since original schema didn't show it;
            // if you want updated_at, add $table->timestamp('UpdatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MedicineCategories');
    }
}
