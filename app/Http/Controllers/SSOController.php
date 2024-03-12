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
        // Retrieve user information from the OAuth provider
        $user = Socialite::driver($provider)->user();

        // Find or create the user in your application
        $user = $this->findOrCreateUser($provider, $user);

        return filament()->getLoginUrl();
    }
    protected function findOrCreateUser($provider, $user)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $user->getId())
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
            throw new EmailTakenException();
        }

        // Create a new user and associated OAuthProvider entry
        return $this->createUser($provider, $user);
    }
    protected function createUser($provider, $sUser)
    {
        $user = User::create([
            'name' => $sUser->getName(),
            'email' => $sUser->getEmail(),
        ]);

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken,
        ]);

        return $user;
    }
}
