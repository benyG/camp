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
        'maxeg', 'maxtg',
    ];
}
