<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('order_items', function (Blueprint $table) {

        //     $table->uuid('id')->primary();
        //     $table->uuid('order_id');

        //     // Product info (food or medicine)
        //     $table->uuid('product_id');
        //     $table->string('product_name');
        //     $table->decimal('price', 10, 2);
        //     $table->integer('quantity');
        //     $table->decimal('total', 10, 2);

        //     $table->timestamps();

        //     $table->foreign('order_id')
        //         ->references('id')
        //         ->on('orders')
        //         ->cascadeOnDelete();
        // });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('product_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
