<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AdClicks', function (Blueprint $table) {

            // Primary key
            $table->uuid('AdClickId')->primary();

            // Columns
            $table->uuid('AdId');
            $table->uuid('UserId')->nullable();
            $table->string('IpAddress', 50);
            $table->string('UserAgent', 500)->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));

            // Foreign keys
            $table->foreign('AdId', 'FK_AdClicks_Ads_AdId')
                  ->references('AdId')->on('Ads')
                  ->onUpdate('NO ACTION')
                  ->onDelete('CASCADE');

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
