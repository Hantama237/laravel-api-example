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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('subscription_id', 255);
            $table->string('noi', 255);
            $table->decimal('amount', 10, 2);
            $table->float('tax')->nullable();
            $table->decimal('amount_w_tax', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['UNPAID', 'PAID', 'OVERDUE']);
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
