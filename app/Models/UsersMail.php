<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsersMail extends Pivot
{
    public $incrementing = false;
    protected $fillable = [
        'last_sent','sent','read'
      ];

}
