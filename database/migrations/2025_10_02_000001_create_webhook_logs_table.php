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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('gateway_name', 255)->nullable();
            $table->string('endpoint', 255)->nullable();
            $table->text('request_header')->nullable();
            $table->text('request_body')->nullable();
            $table->integer('response_status')->nullable();
            $table->text('response_body')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
