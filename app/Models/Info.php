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
    ];
}
