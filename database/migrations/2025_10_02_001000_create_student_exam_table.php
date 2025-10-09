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
        Schema::create('student_exam', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('user_id', 255);
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->timestamp('first_intaken')->nullable();
            $table->timestamp('last_intaken')->nullable();
            $table->string('total_time_intake', 255)->nullable();
            $table->boolean('is_passed');
            $table->enum('status', ['ON GOING', 'FAILED', 'PASSED']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exam');
    }
};
