<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamModule extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;

}
