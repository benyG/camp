<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\UsersMail;
use App\Models\SMail;

class SMail extends Model
{
    public $timestamps = true;
    protected $table = 'smails';
    protected $fillable = [
        'sub','content','from'
      ];
      public function user(): BelongsTo
      {
     //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
          return $this->belongsTo(User::class,'from','id');
      }
      public function users(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(SMail::class, 'users_mail', 'mail', 'user')
          ->as('um')
          ->withPivot('last-sent')
          ->withPivot('sent')
          ->withPivot('id')
          ->using(UsersMail::class);
      }
      public function users1(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(SMail::class, 'users_mail', 'mail', 'user')
          ->as('us1')
          ->wherePivot('user', auth()->user()->id);
      }

}
