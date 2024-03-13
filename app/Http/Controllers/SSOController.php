<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OAuthProvider;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class SSOController extends Controller
{
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
        $user = $this->findOrCreateUser($provider, $user);

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
            'user' => $sUser->getId(),
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
}
