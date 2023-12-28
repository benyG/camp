<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Vague;
use App\Models\UsersMail;
use App\Models\SMail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class User extends Authenticatable implements FilamentUser,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password','ex'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function canAccessPanel(Panel $panel): bool
    {
        //return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return $this->hasVerifiedEmail();
    }
    public function vagueRel(): BelongsTo
    {
   //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(Vague::class,'vague','id');
    }
    public function fmails(): HasMany
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Smail::class, 'from');
    }

    public function dmails(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Smail::class, 'users_mail', 'user', 'mail')
        ->as('um')
        ->withPivot('last-sent')
        ->withPivot('sent')
        ->withPivot('id')
        ->using(UsersMail::class);
    }

}
