<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('RewardTransactions', function (Blueprint $table) {
            $table->uuid('RewardTransactionId')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('CustomerId');
            $table->uuid('OrderId')->nullable();
            $table->string('Type', 20);
            $table->decimal('Amount', 10, 2);
            $table->string('Description', 500);
            $table->integer('Status')->default(2);
            $table->timestampTz('TransactionDate')->default(DB::raw('now()'));
            $table->string('ReferenceId', 100)->nullable();
            $table->string('Notes', 1000)->nullable();

            // Foreign keys
            $table->foreign('CustomerId')
                  ->references('CustomerId')
                  ->on('Customers')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            $table->foreign('OrderId')
                  ->references('OrderId')
                  ->on('Orders')
                  ->onUpdate('no action')
                  ->onDelete('restrict');

            // Indexes
            $table->index('CustomerId', 'IX_RewardTransactions_CustomerId');
            $table->index('OrderId', 'IX_RewardTransactions_OrderId');
            $table->index('ReferenceId', 'IX_RewardTransactions_ReferenceId');
            $table->index('Status', 'IX_RewardTransactions_Status');
            $table->index('TransactionDate', 'IX_RewardTransactions_TransactionDate');
            $table->index('Type', 'IX_RewardTransactions_Type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('RewardTransactions');
    }
};
