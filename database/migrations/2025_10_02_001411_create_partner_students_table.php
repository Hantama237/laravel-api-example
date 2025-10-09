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
        Schema::create('partner_students', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('partner_id', 255)->nullable();
            $table->string('student_id', 255)->nullable();
            $table->timestamp('created_at');
            $table->softDeletes();

            $table->foreign('partner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_students');
    }
};
