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
        Schema::create('quest_ans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('added_at')->useCurrent();
            $table->boolean('isok')->default(false);
            $table->unsignedBigInteger('question');
            $table->unsignedBigInteger('answer');
            $table->foreign('question')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('answer')->references('id')->on('answers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_ans');
    }
};
