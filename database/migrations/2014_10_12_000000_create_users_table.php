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
             $table->timestamp('icx')->nullable();//last ia call
             $table->json('certs')->nullable(); //nb cert for ECA
             $table->boolean('lom')->default(false); //mfa
             $table->boolean('aqa')->default(true); //automatique question explain
             $table->boolean('pa')->default(true); // perf anal
             $table->boolean('itg')->default(true); //intelligent test generation
             $table->boolean('vo')->default(true); //vocal ai k
             $table->string('pk')->defaul('0'); // pck selected
             $table->integer('eca')->default(0)->unsigned(); //eca unit

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
