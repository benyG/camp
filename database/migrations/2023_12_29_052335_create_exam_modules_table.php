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
        Schema::create('exam_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module');
            $table->unsignedBigInteger('exam');
            $table->foreign('module')->references('id')->on('modules')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('exam')->references('id')->on('exams')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_modules');
    }
};
