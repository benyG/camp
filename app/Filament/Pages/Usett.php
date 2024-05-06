<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;

class Usett extends Page implements HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.usett';
    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make(__('form.gs'))->columns(3)
                ->description(__('main.in1'))
                ->schema([
                    Forms\Components\TextInput::make('efrom')->label(__('form.adme'))
                        ->required()->email()->default(env('MAIL_FROM_ADDRESS')),
                    Forms\Components\Toggle::make('smtp')->label(__('form.ase'))
                        ->required()->inline(false)->default(true),
                    Forms\Components\TextInput::make('maxcl')->label(__('form.msc'))
                        ->required()->default(20)->numeric(),
                    Forms\Components\TextInput::make('log')->label(__('form.lgd'))
                        ->required()->default(1)->numeric(),
                ]),
        ])->statePath('data');
    }

    public function create(): void
    {
        $this->info->update($this->form->getState());
        Notification::make()->title(__('form.e30'))->success()->send();
        if ($this->info->wasChanged()) {
            \App\Models\Journ::add(auth()->user(), 'Settings', 3, 'Settings was changed');
        }
    }

    public static function canAccess(): bool
    {
        return auth()->user()->ex > 1 && auth()->user()->ex < 6;
    }
}
