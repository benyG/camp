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
            $table->integer('ix')->default(0)->unsigned(); //ia calls plan
            $table->integer('ix2')->default(0)->unsigned(); //ia calls unit
            $table->timestamp('icx')->nullable(); //last ia call
            $table->json('certs')->nullable(); //nb cert for perf anal
            $table->boolean('lom')->default(false); //mfa
            $table->boolean('aqa')->default(false); //automatique question explain
            $table->boolean('pa')->default(false); // perf anal
            $table->boolean('itg')->default(false); //intelligent test generation
            $table->boolean('vo')->default(false); //vocal ai k
            $table->boolean('vo2')->default(false); //voice type ai
            $table->string('pk')->defaul('0'); // ai lang

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
