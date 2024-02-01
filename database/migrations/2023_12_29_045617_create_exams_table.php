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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('descr')->nullable();
            $table->smallInteger('timer')->default(60)->unsigned();
            $table->smallInteger('quest')->default(100)->unsigned();
            $table->boolean('type')->default(false);
            $table->timestamp('added_at')->useCurrent();
            $table->timestamp('due');
            $table->unsignedBigInteger('from')->nullable();
            $table->foreign('from')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
