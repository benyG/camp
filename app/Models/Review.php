<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'text', 'rev', 'ans',
    ];

    public function questRel(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'quest', 'id');
    }

    public function userRel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function ansRel(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'ans', 'id');
    }
}
