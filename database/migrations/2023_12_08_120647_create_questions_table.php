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
            $table->id();
            $table->text('text');
            $table->text('descr');
            $table->tinyInteger('maxr')->default(4)->unsigned();
            $table->tinyInteger('rev')->default(0)->unsigned();
            $table->boolean('isexam')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('module')->nullable();
            $table->foreign('module')->references('id')->on('modules')->cascadeOnUpdate()->nullOnDelete();
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
