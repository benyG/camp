<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
class Vague extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
      ];
      public function users(): HasMany
      {
         // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
          return $this->hasMany(User::class, 'vague', 'id');
      }

}
