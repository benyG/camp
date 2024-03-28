<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExamModule extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    protected $table = 'exam_modules';

    public function examRel(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam', 'id');
    }

    public function moduleRel2(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module', 'id');
    }
}
