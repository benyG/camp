<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'exp','pbi','sid','amount','type','qte'
    ];
    public function userRel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
    protected function exp(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
            set: fn (string $value) => (new Carbon($value))->setTimezone('UTC')
        );
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (new Carbon($value))->setTimezone(auth()->user()->tz),
        );
    }

}
