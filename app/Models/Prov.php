<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Prov extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
    protected function slug(): Attribute
    {
        return Attribute::make(
            //   get: fn (string $value) => ucfirst($value),
            get: fn (mixed $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }
    public function courses(): HasMany
    {
        // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Course::class, 'prov');
    }

}
