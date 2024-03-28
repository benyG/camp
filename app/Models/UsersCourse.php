<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsersCourse extends Pivot
{
    public $incrementing = true;

    public $timestamps = true;

    public function courseRel(): BelongsTo
    {
        //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Course::class, 'course', 'id');
    }

    public function userRel(): BelongsTo
    {
        //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
