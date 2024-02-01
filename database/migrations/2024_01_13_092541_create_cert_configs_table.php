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
        Schema::create('cert_configs', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('timer')->default(5)->unsigned();
            $table->smallInteger('quest')->default(10)->unsigned();
            $table->json('mods');
            $table->unsignedBigInteger('course');
            $table->foreign('course')->references('id')->on('courses')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cert_configs');
    }
};
