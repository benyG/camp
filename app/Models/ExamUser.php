<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ExamQuest;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class ExamUser extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'gen','added','comp_at','start_at','quest'
      ];
      protected $casts = [
        'gen' => 'array',
    ];
    protected function added(): Attribute
    {
        return Attribute::make(
         get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
      );
    }
    protected function compAt(): Attribute
    {
        return Attribute::make(
         get: fn (?string $value) => empty($value)? "": (new Carbon($value))->setTimezone(auth()->user()->tz),
      );
    }
    protected function startAt(): Attribute
    {
        return Attribute::make(
         get: fn (?string $value) => empty($value)? "": (new Carbon($value))->setTimezone(auth()->user()->tz),
      );
    }

}


