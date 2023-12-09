<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Question;
class Answer extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'text'
      ];
       public function questions(): BelongsToMany
      {
        //
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(Question::class, 'quest_ans', 'answer', 'question')
          ->as('qa')
          ->withPivot('added_at')
          ->withPivot('isok')
          ->withPivot('id')
          ->using(App\Models\QuestAns::class);
        }
}
