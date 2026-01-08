<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AuditLogs', function (Blueprint $table) {
            $table->id('Id'); // bigint auto-increment primary key
            $table->uuid('UserId')->nullable();
            $table->string('Action', 100);
            $table->string('AuditableType', 100);
            $table->string('AuditableId', 50)->nullable();
            $table->text('OldValues')->nullable();
            $table->text('NewValues')->nullable();
            $table->string('IpAddress', 50)->nullable();
            $table->string('Location', 200)->nullable();
            $table->timestampTz('CreatedAt')->default(DB::raw('now()'));
            $table->timestampTz('UpdatedAt')->default(DB::raw('now()'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AuditLogs');
    }
};
