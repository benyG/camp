<?php

namespace Filament\Http\Controllers\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;

class LogoutController
{
    public function __invoke(): LogoutResponse
    {
        \App\Models\Journ::add(auth()->user(), 'Login', 10, 'User logout');
        if(auth()->user()->ex>6) \App\Models\User::destroy(auth()->id());

        Filament::auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
