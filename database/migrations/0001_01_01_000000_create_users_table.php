<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Users', function (Blueprint $table) {
            // UUID primary key
            $table->uuid('UserId')
                  ->primary()
                  ->default(DB::raw('gen_random_uuid()'));

            $table->integer('Role');
            $table->string('Name', 100);
            $table->string('Email', 100);
            $table->string('PasswordHash', 255);
            $table->string('Phone', 15);
            $table->text('AvatarUrl')->nullable();

            $table->boolean('IsActive')->default(true);
            $table->boolean('IsEmailVerified')->default(false);
            $table->boolean('IsBusinessAdmin')->default(false);

            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('DeletedAt')->nullable();

            $table->string('remember_token', 100)->nullable();

            // Indexes
            $table->unique('Email', 'IX_Users_Email');
            $table->unique('Phone', 'IX_Users_Phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
