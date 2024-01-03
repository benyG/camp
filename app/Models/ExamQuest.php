<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Answer;

class ExamQuest extends Pivot
{
    public $incrementing = true;
    public $timestamps = true;
    public function answerRel(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'ans','id');
    }

}
