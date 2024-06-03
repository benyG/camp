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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0)->unsigned();
            // 0 subs
            // 1 ia
            // 2 eca
            $table->tinyInteger('qte')->default(1)->unsigned();
            $table->timestamps();
            $table->timestamp('exp')->useCurrent();
            $table->string('pbi'); // product id
            $table->string('sid'); // stripe id
            $table->string('cus')->nullable();
            $table->smallInteger('amount')->default(0)->unsigned();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
