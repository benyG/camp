<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Module;
use App\Models\Course;
use App\Models\Answer;
use App\Models\QuestAns;
use App\Models\ExamQuest;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Question extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'text','isexam','module','maxr','descr'
      ];
      protected function text2(): Attribute
      {
          return Attribute::make(
           get: fn (mixed $value, array $attributes) => \Illuminate\Support\Str::of($attributes['text'])->stripTags(),
        );
      }
    public function moduleRel(): BelongsTo
    {
    //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Module::class, 'module','id');
        // assuming that parent model key is name 'id'. If not, please specify 'owner_key'
    }
    public function answers(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Answer::class, 'quest_ans', 'question', 'answer')
        ->as('qa')
        ->withPivot('added_at')
        ->withPivot('isok')
        ->withPivot('id');
    }
    public function answers2(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Answer::class, 'quest_ans', 'question', 'answer')
        ->withPivot('isok')
        ->withPivot('id')
        ->wherePivot('isok',true);
    }
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(ExamUser::class, 'exam_quests', 'quest', 'exam')
        ->using(ExamQuest::class);
    }
    public function certif(): HasOneThrough
    {
        return $this->hasOneThrough(
            Course::class,
            Module::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'module', // Local key on the mechanics table...
            'course' // Local key on the cars table...
        );
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'quest');
    }

}
