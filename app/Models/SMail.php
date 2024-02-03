<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\UsersMail;

class SMail extends Model
{
    public $timestamps = true;
    protected $table = 'smails';
    protected $fillable = [
        'sub','content','from'
      ];
      protected $casts = [
        'user' => 'array',
    ];
      public function user(): BelongsTo
      {
     //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
          return $this->belongsTo(User::class,'from','id');
      }
      public function user1(): BelongsTo
      {
     //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
          return $this->belongsTo(User::class,'from','id');
      }
      public function users(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(User::class, 'users_mail', 'mail', 'user')
       //   ->withPivot('last-sent')
      //    ->withPivot('sent')
      //    ->withPivot('id')
          ;
      }
      public function users1(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(User::class, 'users_mail', 'mail', 'user')
          ->withPivot('last_sent')
          ->withPivot('read_date')
          ->withPivot('sent')
          ->withPivot('read')
          ->wherePivot('user', auth()->user()->id);
      }
      public function users2(): BelongsToMany
      {
          //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
          return $this->belongsToMany(User::class, 'users_mail', 'mail', 'user')
          ->withPivot('last_sent')
          ->withPivot('read_date')
          ->withPivot('sent')
         ->withPivot('id');
      }

}
