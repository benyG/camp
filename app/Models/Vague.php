<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vague extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
      ];
      public function users(): BelongsToMany
      {
         // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
          return $this->belongsToMany(User::class,'user_classes', 'clas', 'user');
      }

}
