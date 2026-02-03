<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Customers', function (Blueprint $table) {

            // Primary key
            $table->uuid('CustomerId')->primary();

            // Foreign keys
            $table->uuid('UserId');

            // Columns
            $table->string('Name', 100);
            $table->date('DateOfBirth')->nullable();
            $table->char('Gender', 1)->nullable();
            $table->text('AllergyNotes')->nullable();
            $table->integer('CoinBalance')->default(0);
            $table->string('Address', 100);
            $table->double('DefaultLatitude')->nullable();
            $table->double('DefaultLongitude')->nullable();
            $table->timestampTz('CreatedAt');
            $table->text('ReferralCode')->nullable();
            $table->uuid('ReferredByCustomerId')->nullable();
            $table->boolean('HasReceivedReferralBonus');

            // Indexes
            $table->index(
                'ReferredByCustomerId',
                'IX_Customers_ReferredByCustomerId'
            );

            $table->unique(
                'UserId',
                'IX_Customers_UserId'
            );

            // Foreign key constraints
            $table->foreign('UserId', 'FK_Customers_Users_UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            $table->foreign(
                'ReferredByCustomerId',
                'FK_Customers_Customers_ReferredByCustomerId'
            )
            ->references('CustomerId')
            ->on('Customers')
            ->onUpdate('no action')
            ->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Customers');
    }
};
