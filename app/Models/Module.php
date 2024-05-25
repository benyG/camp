<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

class Module extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'course', 'descr',
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            //   get: fn (string $value) => ucfirst($value),
            get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }

    public function courseRel(): BelongsTo
    {
        //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Course::class, 'course', 'id');
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
        return $this->belongsToMany(Exam::class, 'exam_modules', 'module', 'exam');
    }

    public function provRel(): HasOneThrough
    {
        return $this->hasOneThrough(
            Prov::class,
            Course::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'course', // Local key on the mechanics table...
            'prov' // Local key on the cars table...
        );
    }
}
