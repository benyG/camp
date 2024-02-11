<?php

namespace App\Providers;
use Filament\Http\Responses\Auth\Contracts\EmailVerificationResponse;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
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
        FilamentView::registerRenderHook(
            'panels::scripts.after',
            fn (): string => Blade::render('
            <script>
                if(localStorage.getItem(\'theme\') === null) {
                    localStorage.setItem(\'theme\', \'dark\')
                }
            </script>'),
        );
        /* $this->app->singleton(
            EmailVerificationResponse::class,
            \App\Http\Responses\EmailVerificationResponse::class
        ); */

    }
}
