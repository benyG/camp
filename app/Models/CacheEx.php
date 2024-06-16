<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheEx extends Model
{
    use HasFactory;
    public $incrementing = true;

    protected $fillable = [
        'gen','name'
    ];
}
