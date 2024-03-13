<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OAuthProvider;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;

class SSOController extends Controller
{
    use WithRateLimiting;
    public function redirectToProvider($provider)
    {
        return  Socialite::driver($provider)->redirect();
    }
    public function handleProviderCallback($provider)
    {
        session()->regenerate();
        // Retrieve user information from the OAuth provider
        $user = Socialite::driver($provider)->user();

        // Find or create the user in your application
        $this->login($this->findOrCreateUser($provider, $user));

        return redirect()->to(filament()->getLoginUrl());
    }
    protected function findOrCreateUser($provider, $user)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('user', $user->getId())
            ->first();

        if ($oauthProvider) {
            // Update the access and refresh tokens if needed
            $oauthProvider->update([
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
            ]);

            return $oauthProvider->user;
        }

        if (User::where('email', $user->getEmail())->exists()) {
            // Handle the case where the email is already taken
            session(['auth_opt'=>1]);
            return redirect()->to(filament()->getLoginUrl());
        }

        // Create a new user and associated OAuthProvider entry
        return $this->createUser($provider, $user);
    }
    protected function createUser($provider, $sUser)
    {
        $user =new User;
        $user->name= $sUser->getName();
        $user->email= $sUser->getEmail();
        $user->password= \Illuminate\Support\Facades\Hash::make(now()."xc");
        $user->email_verified_at=now();
        $user->save();

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $user->id,
            'access_token' => $sUser->token,
            'user' => $user->id,
            'refresh_token' => $sUser->refreshToken,
        ]);

        return $user;
    }
    protected function createUser2()
    {
        $user =new User;
        $user->name= "ddd";
        $user->email= "ddd";
        $user->password= \Illuminate\Support\Facades\Hash::make(now()."xc");
        $user->email_verified_at=now();
        $user->save();

        $user->oauthProviders()->create([
            'provider' => "d",
            'provider_user_id' => $user->id,
            'access_token' => "dd",
            'refresh_token' => "ddd",
        ]);

        return $user;
    }
    protected function login($sUser){
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();
            return null;
        }

        if (! Filament::auth()->login($sUser, false)) {
            $txt="Failed login with email ".$sUser->email."
                ";
            \App\Models\Journ::add(null,'Login',5,$txt,session('auth_ip'));
            session(['auth_opt'=>1]);
            return redirect()->to(filament()->getLoginUrl());
        }

        $user = Filament::auth()->user();
        $txt="Successful login of user '$user->name' ($user->email)";
        \App\Models\Journ::add($user,'Login',0,$txt,$this->ox);

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            session(['auth_opt'=>1]);
            return redirect()->to(filament()->getLoginUrl());
        }
        session()->regenerate();
        return redirect()->to(filament()->getLoginUrl());
}
}
