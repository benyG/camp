<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
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
                            'regex' => "There should be at least one special character, and one digit. No spaces",
                        ])                        ,
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
