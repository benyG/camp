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
use App\Models\ExamUser;
use App\Models\Exam;
use App\Models\Course;
use App\Models\UsersCourse;
use App\Models\Review;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;


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
        'password',
        'tz'
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
    protected function createdAt(): Attribute
    {
        return Attribute::make(
         get: fn (string $value) => Carbon::parse($value, auth()->user()->tz),
      );
    }
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
         get: fn (string $value) => Carbon::parse($value, auth()->user()->tz),
      );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        //return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return $this->ax;
    }
    public function vagues(): BelongsToMany
    {
   //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsToMany(Vague::class,'user_classes', 'user', 'clas');
    }
    public function exams(): HasMany
    {
       // return $this->hasMany(App\Models\Module::class, 'foreign_key', 'local_key');
        return $this->hasMany(Exam::class, 'from');
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
    public function exams2(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Exam::class, 'exam_users', 'user', 'exam')
        ->withPivot('added')
        ->withPivot('comp_at')
        ->withPivot('start_at')
        ->withPivot('gen')
         ->withPivot('id')
         ->using(ExamUser::class);
  }
    public function courses(): BelongsToMany
    {
        //return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
        return $this->belongsToMany(Course::class, 'users_course', 'user', 'course')
        ->withPivot('approve');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user');
    }

}
