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
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Gate;

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
        /** START Gates */

        Gate::define('add-course', function (\App\Models\User $user) {
            return $user->eca>$user->courses->count();
        });
        Gate::define('add-exam', function (\App\Models\User $user) {
            $ix= cache()->rememberForever('settings', function () {
                return \App\Models\Info::findOrFail(1);
            });
            $oo=0;
            switch (auth()->user()->ex) {
                case 0 : $oo=500;
                    break;
                case 2 : $oo=$ix->saa_f;
                    break;
                case 3 : $oo=$ix->saa_b;
                    break;
                case 4 : $oo=$ix->saa_s;
                    break;
                case 5 : $oo=$ix->saa_p;
                    break;
                case 9 : $oo=$ix->saa_g;
                    break;
               default: $oo=0; break;
            }

            $op = \App\Models\Exam::has('users1')->with('users1')->where('from',auth()->id())->get()
            ->filter(function(\App\Models\Exam $record,int $key){
                if (empty($record->users1->first()->pivot->start_at)) {
                    return $record->users1->count() > 0 && empty($record->users1->first()->pivot->comp_at) && ! empty($record->due) && now() < $record->due;
                } else {
                    return $record->type == 1 ? ($record->users1->count() > 0 && empty($record->users1->first()->pivot->comp_at) && ! empty($record->due) && now() < $record->due) && $record->timer - now()->diffInMinutes($record->users1->first()->pivot->start_at) > 0 :
                         ($record->users1->count() > 0 && empty($record->users1->first()->pivot->comp_at) && ! empty($record->due) && now() < $record->due);
                }
            })
            ->count();
         //   dd($oo);
            return $oo>$op;
        });
        Gate::define('call-ai', function (\App\Models\User $user) {
            return ($user->ix+$user->ix2)>0;
        });
        Gate::define('vo', function (\App\Models\User $user) {
            $ix= cache()->rememberForever('settings', function () {
                return \App\Models\Info::findOrFail(1);
            });
            $oo=false;
            switch (auth()->user()->ex) {
                case 2:
                    $oo=$ix->sta_f;
                    break;
                case 3:
                    $oo=$ix->sta_b;
                    break;
                case 4:
                    $oo=$ix->sta_s;
                    break;
                case 5:
                    $oo=$ix->sta_p;
                    break;
                case 9:
                    $oo=$ix->sta_g;
                    break;
                default:
                    $oo=false;
                    break;
            }
            return $oo;
        });
        Gate::define('tga', function (\App\Models\User $user) {
            $ix= cache()->rememberForever('settings', function () {
                return \App\Models\Info::findOrFail(1);
            });
            $oo=false;
            switch (auth()->user()->ex) {
                case 2:
                    $oo=$ix->tga_f;
                    break;
                case 3:
                    $oo=$ix->tga_b;
                    break;
                case 4:
                    $oo=$ix->tga_s;
                    break;
                case 5:
                    $oo=$ix->tga_p;
                    break;
                case 9:
                    $oo=$ix->tga_g;
                    break;
                default:
                    $oo=false;
                    break;
            }
            return $oo;
        });
        /** END Gates */

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
        Field::macro("tooltip", function(string $tooltip) {
            return $this->hintAction(
                Action::make('help')
                    ->icon('heroicon-o-question-mark-circle')
                    ->extraAttributes(["class" => "text-gray-500"])
                    ->label("")
                    ->tooltip($tooltip)
            );
        });
    }
}
