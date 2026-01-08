<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AdBillings', function (Blueprint $table) {
            $table->uuid('AdBillingId')->primary();
            $table->uuid('AdId');
            $table->integer('TotalImpressions');
            $table->integer('TotalClicks');
            $table->decimal('AmountDue', 10, 2);
            $table->timestampTz('LastCalculatedAt')->default(DB::raw('now()'));

            // Foreign key
            $table->foreign('AdId')
                ->references('AdId')
                ->on('Ads')
                ->onDelete('cascade');

            // Unique index
            $table->unique('AdId', 'IX_AdBillings_AdId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AdBillings');
    }
};
