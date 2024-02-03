<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Course;
use App\Models\User;
use App\Models\ExamUser;
use App\Models\ExamModule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Exam extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name','type','descr','timer','from','due','quest','certi'
      ];
      protected function slug(): Attribute
      {
          return Attribute::make(
           //   get: fn (string $value) => ucfirst($value),
              get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
          );
      }
    public function modules(): BelongsToMany
      {
          return $this->belongsToMany(Module::class, 'exam_modules', 'exam', 'module')
          ->orderBy('modules.name')
          ->withPivot('nb');
      }
      public function users(): BelongsToMany
      {
          return $this->belongsToMany(User::class, 'exam_users', 'exam', 'user')
          ->withPivot('added')
          ->withPivot('comp_at')
          ->withPivot('start_at')
          ->withPivot('gen')
           ->withPivot('id');
      }
      public function users1(): BelongsToMany
      {
          return $this->belongsToMany(User::class, 'exam_users', 'exam', 'user')
          ->withPivot('added')
          ->withPivot('comp_at')
          ->withPivot('start_at')
          ->withPivot('gen')
           ->withPivot('id')
          ->wherePivot('user', auth()->user()->id);
      }
      public function userRel(): BelongsTo
      {
          return $this->belongsTo(User::class,'from','id');
      }
      public function examods(): HasMany
      {
          return $this->hasMany(ExamModule::class,'exam','id');
      }
      public function certRel(): BelongsTo
      {
        return $this->belongsTo(Course::class,'certi','id');
    }
}
