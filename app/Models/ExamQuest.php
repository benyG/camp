<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamQuest extends Pivot
{
    public $incrementing = true;
    public $timestamps = true;

}
