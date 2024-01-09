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
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('maxt')->default(60)->unsigned();
            $table->smallInteger('mint')->default(10)->unsigned();
            $table->smallInteger('maxs')->default(20)->unsigned();
            $table->smallInteger('maxu')->default(50)->unsigned();
            $table->smallInteger('maxv')->default(80)->unsigned();
            $table->smallInteger('maxp')->default(50)->unsigned();
            $table->smallInteger('maxt')->default(60)->unsigned();
            $table->tinyInteger('wperc')->default(50);
            $table->string('efrom');
            $table->boolean('smtp')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infos');
    }
};
