<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Customers', function (Blueprint $table) {
            $table->uuid('CustomerId')->primary();
            $table->uuid('UserId');
            $table->string('Name', 100);
            $table->date('DateOfBirth')->nullable();
            $table->char('Gender', 1)->nullable();
            $table->text('AllergyNotes')->nullable();
            $table->integer('CoinBalance')->default(0);
            $table->string('MembershipTier', 10);
            $table->string('Address', 100);
            $table->double('DefaultLatitude')->nullable();
            $table->double('DefaultLongitude')->nullable();
            $table->timestampTz('CreatedAt');
            $table->text('ReferralCode')->nullable();
            $table->uuid('ReferredByCustomerId')->nullable();
            $table->boolean('HasReceivedReferralBonus');

            // Foreign keys
            $table->foreign('UserId')
                  ->references('UserId')
                  ->on('Users')
                  ->onUpdate('no action')
                  ->onDelete('cascade');

            $table->foreign('ReferredByCustomerId')
                  ->references('CustomerId')
                  ->on('Customers')
                  ->onUpdate('no action')
                  ->onDelete('no action');

            // Indexes
            $table->index('ReferredByCustomerId', 'IX_Customers_ReferredByCustomerId');
            $table->unique('UserId', 'IX_Customers_UserId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Customers');
    }
};
