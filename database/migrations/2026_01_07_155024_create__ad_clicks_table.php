<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AdClicks', function (Blueprint $table) {
            $table->uuid('AdClickId')->primary();
            $table->uuid('AdId');
            $table->uuid('UserId')->nullable();
            $table->string('IpAddress', 50);
            $table->string('UserAgent', 500)->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Foreign key
            $table->foreign('AdId')
                ->references('AdId')
                ->on('Ads')
                ->onDelete('cascade');

            // Indexes
            $table->index('AdId', 'IX_AdClicks_AdId');
            $table->index('CreatedAt', 'IX_AdClicks_CreatedAt');
            $table->index('UserId', 'IX_AdClicks_UserId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AdClicks');
    }
};
