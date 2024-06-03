<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Exam extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'type', 'descr', 'timer', 'from', 'due', 'quest', 'certi',
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }

    protected function due(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
            set: fn (string $value) => (new Carbon($value))->setTimezone('UTC')
        );
    }

    protected function addedAt(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? (new Carbon($value))->setTimezone(auth()->user()->tz),
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
            ->withPivot('id')->using(ExamUser::class);
    }

    public function users1(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exam_users', 'exam', 'user')
            ->withPivot('added')
            ->withPivot('comp_at')
            ->withPivot('start_at')
            ->withPivot('gen')
            ->withPivot('id')
            ->wherePivot('user', auth()->user()->id)->using(ExamUser::class);
    }

    public function userRel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from', 'id');
    }

    public function examods(): HasMany
    {
        return $this->hasMany(ExamModule::class, 'exam', 'id');
    }

    public function certRel(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'certi', 'id');
    }
}
