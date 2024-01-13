<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Exam;
use App\Models\Module;

class ExamModule extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'exam_modules';
    public function examRel(): BelongsTo
    {
        return $this->belongsTo(Exam::class,'exam','id');
    }

    public function moduleRel(): BelongsTo
    {
        return $this->belongsTo(Module::class,'module','id');
    }

}
