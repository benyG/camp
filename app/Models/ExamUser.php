<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamUser extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'added_at'
      ];

}
