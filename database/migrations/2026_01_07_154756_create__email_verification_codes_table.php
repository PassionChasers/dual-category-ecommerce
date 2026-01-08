<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('EmailVerificationCodes', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('Email', 100);
            $table->string('Code', 500);
            $table->timestampTz('Expires');
            $table->timestampTz('Created');
            $table->boolean('IsUsed');
            $table->timestampTz('UsedAt')->nullable();

            // Indexes
            $table->index('Code', 'IX_EmailVerificationCodes_Code');
            $table->index('Email', 'IX_EmailVerificationCodes_Email');
            $table->index('Expires', 'IX_EmailVerificationCodes_Expires');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('EmailVerificationCodes');
    }
};
