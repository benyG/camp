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
        Schema::create('smails', function (Blueprint $table) {
            $table->id();
            $table->string('sub');
            $table->boolean('hid')->default(false);
            $table->text('content');
            $table->unsignedBigInteger('from')->nullable();
            $table->foreign('from')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smails');
    }
};
