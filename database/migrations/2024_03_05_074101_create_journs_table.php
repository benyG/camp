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
        Schema::create('journs', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('fen')->nullable();
            $table->unsignedBigInteger('user');
            $table->tinyInteger('ac')->default(0)->unsigned();
            $table->timestamps();
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journs');
    }
};
