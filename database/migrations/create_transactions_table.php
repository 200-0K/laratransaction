<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('laratransaction.tables.transactions.name'), function (Blueprint $table) {
            if (config('laratransaction.tables.transactions.uuid')) {
                $table->uuid('id')->primary();
            } else {
                $table->id();
            }

            (
                config('laratransaction.tables.transactionable.uuid')
                ? $table->uuidMorphs('transactionable')
                : $table->morphs('transactionable')
            );

            (
                config('laratransaction.tables.transaction_statuses.uuid')
                ? $table->foreignUuid('status_id')
                : $table->foreignId('status_id')
            )->constrained(config('laratransaction.tables.transaction_statuses.name'));

            (
                config('laratransaction.tables.transaction_types.uuid')
                ? $table->foreignUuid('type_id')
                : $table->foreignId('type_id')
            )->constrained(config('laratransaction.tables.transaction_types.name'));

            (
                config('laratransaction.tables.payment_methods.uuid')
                ? $table->foreignUuid('payment_method_id')
                : $table->foreignId('payment_method_id')
            )->constrained(config('laratransaction.tables.payment_methods.name'));

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('gateway')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('laratransaction.tables.transactions.name'));
    }
};
