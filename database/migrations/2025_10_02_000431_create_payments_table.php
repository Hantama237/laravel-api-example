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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('nop', 255);
            $table->string('invoice_id', 255);
            $table->decimal('amount', 10, 2);
            $table->float('tax');
            $table->decimal('amount_w_tax', 10, 2);
            $table->enum('method', ['STRIPE', 'MOLLIE']);
            $table->date('payment_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
