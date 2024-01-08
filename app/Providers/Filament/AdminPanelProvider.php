<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\Enums\ThemeMode;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;
use App\Filament\Resources\InfoResource;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\Auth\ResetPassword;
use App\Filament\Pages\Auth\EditProfile;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandName('IT Exam Boot Camp')
            ->brandLogo(fn () => view('logo'))
            ->darkModeBrandLogo(fn () => view('logo2'))
            //->brandLogoHeight('3rem')
            ->id('admin')
            ->path('boss')
            ->login()
            ->registration(Register::class)
            ->passwordReset(ResetPassword::class)
            ->emailVerification()
            ->profile(EditProfile::class)
            ->defaultThemeMode(ThemeMode::Dark)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook('panels::body.end',fn (): string => Blade::render('footer'))
            ->renderHook('panels::auth.login.form.after',fn (): string => Blade::render('footer2'))
            ->renderHook('panels::auth.register.form.after',fn (): string => Blade::render('footer2'))
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn (): string => InfoResource::getUrl())
                    ->icon('heroicon-o-cog-6-tooth')
                    ->visible(fn (): bool => auth()->user()->can('viewAny', \App\Models\Info::class)),
                // ...
            ]);
    }
}
