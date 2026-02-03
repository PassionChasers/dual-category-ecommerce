<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Settings', function (Blueprint $table) {

            // Primary key
            $table->integer('Id')->primary();

            // Columns
            $table->text('AppName')->default('Unified App');
            $table->text('AppLogo');
            $table->text('Favicon');
            $table->text('MetaTitle');
            $table->text('MetaDescription');
            $table->text('ContactEmail')->default('passionchasers.it@gmail.com');
            $table->text('ContactPhone');
            $table->text('ContactAddress');
            $table->text('SmsApiUrl');
            $table->text('SmsApiKey');
            $table->text('SmsSenderId');
            $table->text('MailMailer');
            $table->text('MailHost');
            $table->integer('MailPort')->nullable();
            $table->text('MailUsername');
            $table->text('MailPassword');
            $table->text('MailEncryption');
            $table->text('MailFromAddress');
            $table->text('MailFromName');
            $table->text('FacebookUrl');
            $table->text('TwitterUrl');
            $table->text('LinkedInUrl');
            $table->text('InstagramUrl');
            $table->boolean('MaintenanceMode')->default(false);
            $table->timestampTz('CreatedAt');
            $table->timestampTz('UpdatedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Settings');
    }
};
