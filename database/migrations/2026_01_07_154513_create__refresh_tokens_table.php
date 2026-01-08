<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('RefreshTokens', function (Blueprint $table) {
            $table->uuid('Id')->primary();
            $table->uuid('UserId');
            $table->string('Token', 500);
            $table->timestampTz('Expires');
            $table->timestampTz('Created');
            $table->string('CreatedByIp', 45);
            $table->timestampTz('Revoked')->nullable();
            $table->text('RevokedByIp')->nullable();
            $table->text('ReplacedByToken')->nullable();
            $table->string('ReasonRevoked', 200)->nullable();
            $table->string('DeviceInfo', 200);

            // Foreign key
            $table->foreign('UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            // Indexes
            $table->index('Expires', 'IX_RefreshTokens_Expires');
            $table->index('Token', 'IX_RefreshTokens_Token');
            $table->index('UserId', 'IX_RefreshTokens_UserId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('RefreshTokens');
    }
};
