<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $view = 'filament.auth.login';

    public $ox;

    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
        ])->statePath('data');
    }

    protected function getPasswordFormComponent(): Component
    {
        return Forms\Components\TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    public function getHeading(): string
    {
        return __('main.log');
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
        if (! empty(session('auth_opt')) && session('auth_opt') == 1) {
            $this->throwFailureValidationException2();
        }
        session(['auth_opt' => null]);
    }

    protected function throwFailureValidationException2(): never
    {
        session(['auth_opt' => null]);
        throw ValidationException::withMessages([
            'data.email' => __('form.e2'),
        ]);
    }

    public function dehydrate()
    {
        session(['auth_ip' => $this->ox]);
    }

    public function authenticate(): ?LoginResponse
    {
        //   dd($this->ox);
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
        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $txt = 'Failed login with email '.$data['email'];
            \App\Models\Journ::add(null, 'Login', 5, $txt, $this->ox);
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();
        $txt = "Successful login of user '$user->name' ($user->email)";
        \App\Models\Journ::add($user, 'Login', 0, $txt, $this->ox);

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }
        $user->kx = Str::random(180);
        if (auth()->check() && auth()->user()->ex > 1) {
            $ix = cache()->rememberForever('settings', function () {
                return \App\Models\Info::findOrFail(1);
            });
            //plan
            $user = auth()->user();
            if (is_null($user->sub) || $user->sub->exp < now()) {
                $user->ex = 2;
                $user->aqa= false;
                if (!$ix->sta_f) {
                    $user->vo = false; $user->vo2=false;
                }
                //pa
                if (!$ix->pa_f) {
                    $user->pa = false;
                }
                //tga
                if (!$ix->tga_f) {
                    $user->itg = false;
                }
            } else {
                $ex = 0; $iac = 0;$vo=false;$itg=false;$pa=false;
                switch ($user->sub->pbi) {
                    case $ix->bp_id : $ex = 3;$iac = $ix->iac_b;$vo=$ix->sta_b;
                    $itg=$ix->tga_b;$pa=$ix->pa_b;
                        break;
                    case $ix->sp_id : $ex = 4;$iac = $ix->iac_s;$vo=$ix->sta_s;
                    $itg=$ix->tga_s;$pa=$ix->pa_s;
                        break;
                    case $ix->pp_id : $ex = 5; $iac = $ix->iac_p;$vo=$ix->sta_p;
                    $itg=$ix->tga_p;$pa=$ix->pa_p;
                        break;
                    default: $ex = 2; $iac = $ix->iac_f;$vo=$ix->sta_f;
                    $itg=$ix->tga_f;$pa=$ix->pa_f;
                        break;
                }
                if ($user->ex != $ex) {
                    $user->ex = $ex;
                }
                if ($user->ex != 4 && $user->ex != 5) {
                    $user->aqa = false;
                }

                //ia
                $rs=fmod(intval($user->sub->created_at->diffInDays(now())),30);
                //dd($rs);
                if (is_null($user->icx) || \Illuminate\Support\Carbon::parse($user->icx)->diffInDays(now())>$rs) {
                    $user->ix = $iac;
                }
                if (!$vo) {
                    $user->vo = false; $user->vo2=false;
                }
                //pa
                if (!$pa) {
                    $user->pa = false;
                }
                //tga
                if (!$itg) {
                    $user->itg = false;
                }
            }
        }
        $user->save();

        session()->regenerate();

        return app(LoginResponse::class);
    }

    //Login Guest
    public function authenticate2(): ?LoginResponse
    {
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
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        $user = new \App\Models\User;
        $user->name = 'Guest';
        $user->email = 'user'.Str::remove(['-', ' ', ':'], now().'').Str::random(3).'@examboot.net';
        $sp = Str::random(20);
        $user->password = Hash::make($sp);
        $user->email_verified_at = now();
        $user->ex = 9;
        $user->ix = $ix->iac_g;
        $user->ax = 1;
        $user->tz = isset(session('auth_ip')['timezone']) ? session('auth_ip')['timezone'] : 'UTC';
        //plan
        $user->ix = $ix->iac_g;
        $user->aqa= true; $user->vo2= false;
        $user->pa=$ix->pa_g;$user->itg=$ix->tga_g;$user->vo=$ix->sta_g;

        $user->save();
        if (! Filament::auth()->attempt([
            'email' => $user->email,
            'password' => $sp,
        ], false)) {
            Notification::make()
                ->title(__('form.e10'))->danger()->send();
            $txt = 'Failed login with email '.$user->email;
            \App\Models\Journ::add(null, 'Login', 5, $txt, $this->ox);
        }

        $user = Filament::auth()->user();
        $txt = "Successful login of a guest '$user->name' ($user->email)";
        \App\Models\Journ::add($user, 'Login', 0, $txt, $this->ox);

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();
        }
        session()->regenerate();

        return app(LoginResponse::class);
    }
}
