<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AdBillings', function (Blueprint $table) {

            // Primary key
            $table->uuid('AdBillingId')->primary();

            // Columns
            $table->uuid('AdId');
            $table->integer('TotalImpressions');
            $table->integer('TotalClicks');
            $table->decimal('AmountDue', 10, 2);
            $table->timestampTz('LastCalculatedAt')->default(DB::raw('now()'));

            // Foreign key
            $table->foreign('AdId', 'FK_AdBillings_Ads_AdId')
                  ->references('AdId')->on('Ads')
                  ->onUpdate('NO ACTION')
                  ->onDelete('CASCADE');

            // Indexes
            $table->unique('AdId', 'IX_AdBillings_AdId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AdBillings');
    }
};
