<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms;

/**
 * @property Form $form
 */
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent()
                ->regex('/^\S*(?=.*\d)(?=\S*[\W])[a-zA-Z\d]\S*$/i')
                ->validationMessages([
                    'regex' => "There should be at least one special character, and one digit. No spaces",
                ]),
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
            ]);
            /* Min 8 chars long
Min One Digit
Min One Uppercase
Min One Lower Case
Min One Special Chars
/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/ */
    }
}
