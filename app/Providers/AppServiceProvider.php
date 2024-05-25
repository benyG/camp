<?php

namespace App\Providers;

use Filament\Http\Responses\Auth\Contracts\EmailVerificationResponse;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'violet' => Color::Violet,
            'cyan' => Color::Cyan,
            'yellow' => Color::Yellow,
            'lime' => Color::Lime,
            'pink' => Color::Pink,
            'stone' => Color::Stone,
            'purple' => Color::Purple,
        ]);
        FilamentView::registerRenderHook(
            'panels::scripts.after',
            fn (): string => Blade::render('
            <script>
                if(localStorage.getItem(\'theme\') === null) {
                    localStorage.setItem(\'theme\', \'dark\')
                }
            </script>'),
        );
        FilamentAsset::register([
            Css::make('extra', asset('css/extra.css')),
            Js::make('extra', asset('js/extra.js')),
        ]);
        /* $this->app->singleton(
            EmailVerificationResponse::class,
            \App\Http\Responses\EmailVerificationResponse::class
        ); */

    }
}
