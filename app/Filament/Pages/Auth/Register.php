<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms;

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
}
