<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
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

}
