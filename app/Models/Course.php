<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Module;
class Course extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name'
      ];
    protected function Slug(): Attribute
    {
        return Attribute::make(
         //   get: fn (string $value) => ucfirst($value),
            get: fn (string $value, array $attributes) => Str::slug($attributes['name'], '-')
        );
    }
    public function modules(): HasMany
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Module::class, 'course');
    }
}
