<?php

namespace App\Http\Controllers;

use App\Models\OAuthProvider;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Exception;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class StripeController extends Controller
{
    public function handleProviderCallback($provider)
    {
        session()->regenerate();
        try {
            $user = Str::contains($provider, 'linkedin') ? Socialite::driver('linkedin-openid')->user() : Socialite::driver($provider)->user();
        } catch (Exception $exception) {
            report($exception);

            return redirect()->to(filament()->getLoginUrl());
        }

        // Find or create the user in your application
        $us = $this->findOrCreateUser($provider, $user);
        if (($us instanceof User)) {
            $this->login($us, $provider);
        }

        return redirect()->to(filament()->getLoginUrl());
    }

}
