<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Course extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'pub', 'descr',
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            //   get: fn (string $value) => ucfirst($value),
            get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }

    public function provRel(): BelongsTo
    {
        //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Prov::class, 'prov', 'id');
    }

    public function modules(): HasMany
    {
        // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Module::class, 'course');
    }

    public function exams(): HasMany
    {
        // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Exam::class, 'certi');
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
            ->withPivot('approve');
    }

    public function users1(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(User::class, 'users_course', 'course', 'user')
            ->withPivot('id')
            ->withPivot('approve')
            ->wherePivot('user', auth()->user()->id);
    }

    public function users2(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(User::class, 'users_course', 'course', 'user')
            ->withPivot('id')
            ->withPivot('created_at')
            ->withPivot('approve')
            ->wherePivot('approve', false)
            ->latest('users_course.created_at');
    }

    public function configs(): HasMany
    {
        // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(CertConfig::class, 'course');
    }
}
