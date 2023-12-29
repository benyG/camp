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
        Schema::create('exam_quests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('ans');
            $table->unsignedBigInteger('quest');
            $table->unsignedBigInteger('exam');
            $table->foreign('ans')->references('id')->on('answers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('quest')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('exam')->references('id')->on('exam_users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_quests');
    }
};
