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
        Schema::create('payment_gateway_callbacks', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('payment_id', 255);
            $table->string('gateway_name', 255);
            $table->string('gateway_transaction_id', 255);
            $table->json('raw_data');
            $table->integer('status_code');
            $table->boolean('is_proceed');
            $table->timestamp('proceed_at');
            $table->timestamp('created_at');

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_callbacks');
    }
};
