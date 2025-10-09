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
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['MULTIPLE_OPTION', 'MULTIPLE_SELECT', 'DRAG_AND_DROP_OPTION', 'DRAG_AND_DROP_ORDER']);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->text('image_url')->nullable();
            $table->text('video_url')->nullable();
            $table->integer('sequence');
            $table->integer('passed_count');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
