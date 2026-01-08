
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users table
         Schema::create('Users', function (Blueprint $table) {
            $table->uuid('UserId')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('Role', 20);
            $table->string('Name', 100);
            $table->string('Email', 100);
            $table->string('PasswordHash', 255);
            $table->string('Phone', 15);
            $table->text('AvatarUrl')->nullable();
            $table->boolean('IsActive');
            $table->boolean('IsEmailVerified');
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('DeletedAt')->nullable();
            $table->boolean('IsBusinessAdmin');
            $table->string('remember_token', 100)->nullable();

            // Indexes
            $table->unique('Email', 'IX_Users_Email');
            $table->unique('Phone', 'IX_Users_Phone');
        });

        // Password reset tokens table
        Schema::create('PasswordResetCodes', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->string('Email', 100);
            $table->string('Code', 500);
            $table->timestampTz('Expires');
            $table->timestampTz('Created');
            $table->boolean('IsUsed');
            $table->timestampTz('UsedAt')->nullable();

            // Indexes
            $table->index('Code', 'IX_PasswordResetCodes_Code');
            $table->index('Email', 'IX_PasswordResetCodes_Email');
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('PasswordResetCodes');
        Schema::dropIfExists('Users');
    }
};

