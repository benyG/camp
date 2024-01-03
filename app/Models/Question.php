<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Module;
use App\Models\Answer;
use App\Models\QuestAns;
use App\Models\ExamQuest;
class Question extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'text','isexam','module','maxr','descr'
      ];
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
        ->withPivot('id')
        ->using(QuestAns::class);
    }
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(ExamUser::class, 'exam_quests', 'quest', 'exam')
        ->using(ExamQuest::class);
    }

}
