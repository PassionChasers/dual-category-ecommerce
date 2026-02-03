<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Ads', function (Blueprint $table) {

            // Primary key
            $table->uuid('AdId')->primary();

            // Columns
            $table->string('Title', 200);
            $table->string('ImageUrl', 500);
            $table->string('RedirectUrl', 500);
            $table->string('AdvertiserName', 200);
            $table->string('Description', 1000)->nullable();
            $table->boolean('IsActive');
            $table->timestampTz('StartDate');
            $table->timestampTz('EndDate')->nullable();
            $table->decimal('CostPerClick', 10, 2);
            $table->decimal('CostPerThousandImpressions', 10, 2);
            $table->decimal('TotalBudget', 10, 2);
            $table->integer('TotalImpressions');
            $table->integer('TotalClicks');
            $table->decimal('AmountSpent', 10, 2)->default(0.0);
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('UpdatedAt')->default(DB::raw('now()'));

            // Indexes
            $table->index('AdvertiserName', 'IX_Ads_AdvertiserName');
            $table->index('EndDate', 'IX_Ads_EndDate');
            $table->index('IsActive', 'IX_Ads_IsActive');
            $table->index('StartDate', 'IX_Ads_StartDate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Ads');
    }
};
