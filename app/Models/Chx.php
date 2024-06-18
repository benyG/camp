<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chx extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'pli', 'rli', 'sid','i1','i2','i3','i4',
    ];

}
