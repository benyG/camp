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
            $table->tinyInteger('iac_f')->default(10)->unsigned();
            $table->tinyInteger('iac_b')->default(10)->unsigned();
            $table->tinyInteger('iac_s')->default(10)->unsigned();
            $table->tinyInteger('iac_p')->default(10)->unsigned();
            $table->tinyInteger('saa_f')->default(1)->unsigned();
            $table->tinyInteger('saa_b')->default(1)->unsigned();
            $table->tinyInteger('saa_s')->default(1)->unsigned();
            $table->tinyInteger('saa_p')->default(1)->unsigned();
            $table->tinyInteger('eca_f')->default(1)->unsigned();
            $table->tinyInteger('eca_b')->default(1)->unsigned();
            $table->tinyInteger('eca_s')->default(1)->unsigned();
            $table->tinyInteger('eca_p')->default(1)->unsigned();
            $table->boolean('tec_f')->default(false);
            $table->boolean('tec_b')->default(false);
            $table->boolean('tec_s')->default(false);
            $table->boolean('tec_p')->default(false);
            $table->boolean('ftg_f')->default(false);
            $table->boolean('ftg_b')->default(false);
            $table->boolean('ftg_s')->default(false);
            $table->boolean('ftg_p')->default(false);
            $table->boolean('pa_f')->default(false);
            $table->boolean('pa_b')->default(false);
            $table->boolean('pa_s')->default(false);
            $table->boolean('pa_p')->default(false);
            $table->boolean('tga_f')->default(false);
            $table->boolean('tga_b')->default(false);
            $table->boolean('tga_s')->default(false);
            $table->boolean('tga_p')->default(false);
            $table->boolean('sta_f')->default(false);
            $table->boolean('sta_b')->default(false);
            $table->boolean('sta_s')->default(false);
            $table->boolean('sta_p')->default(false);
            $table->string('efrom');
            $table->boolean('smtp')->default(true);
            $table->tinyInteger('var1')->default(0);
            $table->text('apk')->nullable();
            $table->text('endp')->nullable();
            $table->text('model')->nullable();
            $table->text('cont1')->nullable();
            $table->text('cont2')->nullable();
            $table->text('cont3')->nullable();
            $table->text('cont4')->nullable();
            $table->text('cont5')->nullable();
            $table->text('cont6')->nullable();
            $table->text('cont7')->nullable();
            $table->text('cont8')->nullable();
            $table->text('cont9')->nullable();
            $table->tinyInteger('taff')->default(30)->unsigned();
            $table->tinyInteger('log')->default(1)->unsigned();
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
