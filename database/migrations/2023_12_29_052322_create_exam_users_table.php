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
        Schema::create('exam_users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('added')->useCurrent();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('comp_at')->nullable();
            $table->json('gen')->nullable();
            $table->json('quest')->nullable();
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('exam');
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('exam')->references('id')->on('exams')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_users');
    }
};
