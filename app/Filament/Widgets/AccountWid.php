<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
class AccountWid extends Widget implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    protected static ?int $sort = -3;

    protected static string $view = 'filament.widgets.account-widget';
    public function priAction(): Action
    {
        return Action::make('inv')->label(__('form.upg'))
        ->url(fn (): string => \App\Filament\Pages\Pricing::getUrl())
        ->color('primary')
        ->visible(fn (): bool => auth()->user()->ex!=0 && auth()->user()->ex!=1)
        ;
    }

}
