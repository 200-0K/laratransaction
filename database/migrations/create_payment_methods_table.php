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
        Schema::create(config('laratransaction.tables.payment_methods.name'), function (Blueprint $table) {
            if (config('laratransaction.tables.payment_methods.uuid')) {
                $table->uuid('id')->primary();
            } else {
                $table->id();
            }

            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('laratransaction.tables.payment_methods.name'));
    }
};
