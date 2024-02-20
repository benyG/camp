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
            $table->smallInteger('mint')->default(15)->unsigned();
            $table->smallInteger('maxs')->default(10)->unsigned();
            $table->smallInteger('maxu')->default(50)->unsigned();
            $table->smallInteger('maxv')->default(200)->unsigned();
            $table->smallInteger('maxp')->default(100)->unsigned();
            $table->smallInteger('minq')->default(5)->unsigned();
            $table->smallInteger('maxts')->default(20)->unsigned();
            $table->smallInteger('maxtu')->default(60)->unsigned();
            $table->smallInteger('maxtv')->default(240)->unsigned();
            $table->smallInteger('maxtp')->default(120)->unsigned();
            $table->smallInteger('maxes')->default(10)->unsigned();
            $table->smallInteger('maxeu')->default(30)->unsigned();
            $table->smallInteger('maxev')->default(50)->unsigned();
            $table->smallInteger('maxep')->default(100)->unsigned();
            $table->tinyInteger('wperc')->default(50)->unsigned();
            $table->tinyInteger('maxcl')->default(20)->unsigned();
            $table->string('efrom');
            $table->boolean('smtp')->default(true);
            $table->tinyInteger('var1')->default(0);
            $table->text('apk')->nullable();
            $table->text('endp')->nullable();
            $table->text('model')->nullable();
            $table->text('cont1')->nullable();
            $table->text('cont2')->nullable();
            $table->text('cont3')->nullable();

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
