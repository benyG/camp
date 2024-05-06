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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->tinyInteger('ex')->default(2)->unsigned();
            $table->boolean('ax')->default(true);
            $table->string('kx')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('vague')->nullable();
            $table->rememberToken();
            //pck
             $table->integer('ix')->default(0)->unsigned(); //ia calls
             $table->timestamp('icx')->nullable();
             $table->json('certs')->nullable();
             $table->boolean('aqa')->default(true);
             $table->boolean('pa')->default(true);
             $table->boolean('itg')->default(true);

           $table->timestamps();
            $table->foreign('vague')->references('id')->on('vagues')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
