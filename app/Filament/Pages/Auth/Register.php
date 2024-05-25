<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Get;

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
                                'regex' => __('form.e1'),
                            ]),
                        $this->getPasswordConfirmationFormComponent(),
                        Forms\Components\Select::make('tz')->label(__('form.tz'))->required()
                            ->options(function () {
                                $oo = mtz();
                                $ap = [];
                                foreach ($oo as $timezone) {
                                    $ap[$timezone['timezone']] = '(GMT '.$timezone['offset'].') '.$timezone['name'];
                                }

                                return $ap;
                            }),
                        Forms\Components\Select::make('pck')->label('Package')->required()
                            ->options(['0'=>"Free",'1'=>"Basic",'2'=>"Standard",'3'=>"Premium"])->live(),
                        Forms\Components\Select::make('bil')->label(__('form.bil'))->required(fn(Get $get):bool=>intval($get('pck')!=0))
                            ->options(['0'=>__('form.mon'),'1'=>__('form.ann')])->visible(fn(Get $get):bool=>intval($get('pck')!=0)),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public function dehydrate()
    {
        session(['auth_ip' => $this->ox]);
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
        $pck=intval($data['pck']);
        $user->pk=$pck.($pck>0?'|'.intval($data['bil']):'');$user->save();
        $txt = 'New user registered with email '.$data['email'];
        \App\Models\Journ::add(null, 'Register', 1, $txt, $this->ox);

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
