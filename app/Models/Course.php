<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\User;
use App\Models\UsersCourse;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name','pub','descr'
      ];
    protected function slug(): Attribute
    {
        return Attribute::make(
         //   get: fn (string $value) => ucfirst($value),
            get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }
    public function modules(): HasMany
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Module::class, 'course');
    }
    public function questions(): HasManyThrough
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
       return $this->through('modules')->has('questions');
    }
    public function users(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(User::class, 'users_course', 'course', 'user')
        ->using(UsersCourse::class);
    }
    public function users1(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(User::class, 'users_course', 'course', 'user')
    ->wherePivot('user', auth()->user()->id)
        ->using(UsersCourse::class);
    }
}
