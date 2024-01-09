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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('maxs')->default(20)->unsigned();
            $table->smallInteger('maxu')->default(50)->unsigned();
            $table->smallInteger('maxv')->default(80)->unsigned();
            $table->smallInteger('maxp')->default(50)->unsigned();
            $table->unsignedBigInteger('course')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->foreign('course')->references('id')->on('courses')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
