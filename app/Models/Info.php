<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'wperc', 'smtp', 'maxu', 'maxs', 'maxv', 'maxp', 'mint', 'minq', 'efrom',
        'maxtu', 'maxts', 'maxtv', 'maxtp', 'maxeu', 'maxes', 'maxev', 'maxep',
        'apk', 'endp', 'cont1', 'cont2', 'cont3', 'maxcl', 'model', 'taff',
        'cont4', 'cont5', 'cont3', 'cont6', 'cont7', 'cont8', 'cont9',
        'iac_f', 'saa_f', 'tec_f', 'ftg_f', 'tga_f', 'sta_f', 'pa_f', 'eca_f',
        'iac_b', 'saa_b', 'tec_b', 'ftg_b', 'tga_b', 'sta_b', 'pa_b', 'eca_b',
        'iac_s', 'saa_s', 'tec_s', 'ftg_s', 'tga_s', 'sta_s', 'pa_s', 'eca_s',
        'iac_p', 'saa_p', 'tec_p', 'ftg_p', 'tga_p', 'sta_p', 'pa_p', 'eca_p',
        'maxeg', 'maxtg','bp_id','bp_amm','bp_amy','bp_ml','bp_yl','sp_id','sp_amm',
        'sp_amy','sp_ml','sp_yl','pp_id','pp_amm','pp_amy','pp_ml','pp_yl','iac1_id',
        'iac1_am','iac1_li','iac2_id','iac2_am','iac2_li','iac3_id','iac3_am','iac3_li',
        'eca_id','eca_am','eca_li','iac1_qt','iac2_qt','iac3_qt','eac_qt'
    ];
}
