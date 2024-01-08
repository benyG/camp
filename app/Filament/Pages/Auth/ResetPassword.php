<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;

class ResetPassword extends BaseResetPassword
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent()
                ->regex('/^\S*(?=.*\d)(?=\S*[\W])[a-zA-Z\d]\S*$/i')
                ->validationMessages([
                    'regex' => "There should be at least one special character, and one digit. No spaces",
                ]),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
