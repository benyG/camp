<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Ann extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'text','descr','url','type','hid','due'
      ];
      protected $casts = [
        'type' => 'array',
    ];
    protected function createdAt(): Attribute
    {
        return Attribute::make(
         get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
      );
    }
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
         get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
      );
    }
    protected function due(): Attribute
    {
        return Attribute::make(
         get: fn (?string $value) => isset($value)?(new Carbon($value))->setTimezone(auth()->user()->tz):null,
         set: fn (?string $value) => isset($value)?(new Carbon($value))->setTimezone("UTC"):null
      );
    }

}
