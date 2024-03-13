<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    public $ox;
    protected static string $view = 'filament.auth.register';
    public function form(Form $form): Form
    {
        return $form;
    }
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent()
                        ->regex('/^\S*(?=.*\d)(?=\S*[\W])[a-zA-Z\d]\S*$/i')
                        ->validationMessages([
                            'regex' => "There should be at least one uppercase and lowercase letter, one special character, and one digit. No spaces",
                        ])                        ,
                        $this->getPasswordConfirmationFormComponent(),
                        Forms\Components\Select::make('tz')->label('Timezone')->required()
                        ->options(function(){
                            $oo=mtz();
                            $ap=array();
                            foreach ($oo as $timezone) {
                                $ap[$timezone['timezone']]= '(GMT '.$timezone['offset'].') '.$timezone['name'];
                            }
                            return $ap;
                        })
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    public function dehydrate()
    {
        session(['auth_ip'=>$this->ox]);
    }
    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->getUserModel()::create($data);
        $txt="New user registered with email ".$data["email"];
        \App\Models\Journ::add(null,'Register',1,$txt,$this->ox);

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
