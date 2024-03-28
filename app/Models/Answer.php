<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Answer extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'text',
    ];

    public function questions(): BelongsToMany
    {
        //D'acc
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Question::class, 'quest_ans', 'answer', 'question')
            ->as('qa')
            ->withPivot('added_at')
            ->withPivot('isok')
            ->withPivot('id')
            ->using(\App\Models\QuestAns::class);
    }

    public function exams(): HasMany
    {
        // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(ExamQuest::class, 'ans', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'ans');
    }
}
