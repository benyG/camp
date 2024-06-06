<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
class Guest extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'guest';

    public function mount()
    {
        if(auth()->user()->ex==9){
        \Filament\Facades\Filament::auth()->logout();
        session()->forget('lastActivityTime');
        session()->invalidate();
        session()->regenerateToken();

        return redirect(filament()->getRegistrationUrl());
        }else abort(403);
    }

}
