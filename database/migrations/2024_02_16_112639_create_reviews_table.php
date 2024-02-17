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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->boolean('rev')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('quest');
            $table->foreign('quest')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
