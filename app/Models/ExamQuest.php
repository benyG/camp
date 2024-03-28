<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamQuest extends Pivot
{
    public $incrementing = true;

    public $timestamps = true;

    public function answerRel(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'ans', 'id');
    }
}
