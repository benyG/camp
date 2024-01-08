<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'wperc','smtp','maxt','maxu','maxs','maxv','maxp','mint','minq'
      ];
}
