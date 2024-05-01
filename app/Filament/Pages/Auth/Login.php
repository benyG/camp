<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\Component;
use Filament\Forms;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
        $user->kx=Str::random(180);$user->save();
        session()->regenerate();

        return app(LoginResponse::class);
    }
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
        $user= new \App\Models\User;
        $user->name="Guest";
        $user->email='user'.Str::remove(['-',' ',':'], now()."").Str::random(3).'@itexambootcamp.com';
        $sp=Str::random(20);
        $user->password=Hash::make($sp);
        $user->email_verified_at=now();
        $user->ex=9;$user->ax=1;
        $user->tz = isset(session('auth_ip')['timezone']) ? session('auth_ip')['timezone'] : 'UTC';
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
