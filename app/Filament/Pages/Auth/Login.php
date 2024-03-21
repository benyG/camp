<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $view = 'filament.auth.login';

    public $ox;
    public function form(Form $form): Form
    {
        return $form;
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
      if(!empty(session('auth_opt')) && session('auth_opt')==1)  $this->throwFailureValidationException2();
      session(['auth_opt'=>null]);
    }
    protected function throwFailureValidationException2(): never
    {
        session(['auth_opt'=>null]);
        throw ValidationException::withMessages([
            'data.email' => __('form.e2'),
        ]);
    }
    public function dehydrate()
    {
        session(['auth_ip'=>$this->ox]);
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
            $txt="Failed login with email ".$data["email"]."
                ";
            \App\Models\Journ::add(null,'Login',5,$txt,$this->ox);
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();
        $txt="Successful login of user '$user->name' ($user->email)";
        \App\Models\Journ::add($user,'Login',0,$txt,$this->ox);

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }
        session()->regenerate();

        return app(LoginResponse::class);
    }
}
