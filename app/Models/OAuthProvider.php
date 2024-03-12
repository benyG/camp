<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class OAuthProvider extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'provider',
        'access_token',
        'refresh_token',
        'provider_user_id'
    ];
    public function userRel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_user_id','id');
    }

}
