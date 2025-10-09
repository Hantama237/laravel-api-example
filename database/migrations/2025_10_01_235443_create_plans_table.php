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
        Schema::create('plans', function (Blueprint $table) {
            $table->smallInteger('id')->unsigned()->autoIncrement();
            $table->string('name', 255);
            $table->enum('type', ['B2C', 'B2B']);
            $table->tinyInteger('slot')->unsigned()->nullable();
            $table->string('duration', 255)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_enable')->default(false);
            $table->boolean('is_built_in')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
