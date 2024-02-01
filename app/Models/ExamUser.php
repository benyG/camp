<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ExamQuest;

class ExamUser extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'gen'
      ];
      protected $casts = [
        'gen' => 'array',
    ];
      public function questions(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(Question::class, 'exam_quests', 'exam', 'quest')
          ->using(ExamQuest::class);
      }

}
