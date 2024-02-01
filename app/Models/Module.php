<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Module extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name','course',
      ];
    protected function Slug(): Attribute
    {
        return Attribute::make(
         //   get: fn (string $value) => ucfirst($value),
         get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }
    public function courseRel(): BelongsTo
    {
   //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Course::class,'course','id');
    }
    public function questions(): HasMany
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Question::class, 'module', 'id');
    }
    public function exams(): BelongsToMany
    {
      //D'acc
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Exam::class, 'exam_modules', 'module', 'exam')->using(\App\Models\ExamModule::class);
      }
}
