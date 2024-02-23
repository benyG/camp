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
        Schema::create('anns', function (Blueprint $table) {
            $table->id();
            $table->string('descr')->nullable;
            $table->string('url',500)->nullable();
            $table->boolean('hid')->default(true);
            $table->text('text');
            $table->text('type');
            $table->timestamp('due')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anns');
    }
};
