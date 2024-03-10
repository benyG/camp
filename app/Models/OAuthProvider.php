<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
