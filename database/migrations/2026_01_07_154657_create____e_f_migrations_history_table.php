<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('__EFMigrationsHistory', function (Blueprint $table) {

            // Columns
            $table->string('MigrationId', 150)->primary();
            $table->string('ProductVersion', 32);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('__EFMigrationsHistory');
    }
};
