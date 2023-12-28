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
        Schema::create('users_mail', function (Blueprint $table) {
            $table->id();
            $table->timestamp('last_sent')->useCurrent()->nullable();
            $table->timestamp('read_date')->useCurrent()->nullable();
            $table->boolean('sent')->default(false);
            $table->boolean('read')->default(false);
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('mail');
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('mail')->references('id')->on('smails')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_mail');
    }
};
