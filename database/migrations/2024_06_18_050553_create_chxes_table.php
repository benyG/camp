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
        Schema::create('chxes', function (Blueprint $table) {
            $table->id();
            $table->text('pli')->nullable();
            $table->text('rli')->nullable();
            $table->string('sid')->nullable();
            $table->string('i1')->nullable();
            $table->string('i2')->nullable();
            $table->text('i3')->nullable();
            $table->text('i4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chxes');
    }
};
