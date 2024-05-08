<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Support\Enums\VerticalAlignment;

class Usett extends Page implements HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static string $view = 'filament.pages.usett';
    public ?array $data = [];
    protected static ?int $navigationSort = 500;
    protected static ?string $navigationGroup = 'Administration';

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }
    public static function getNavigationLabel(): string
    {
        return trans_choice('main.m9', 2);
    }
    public function getTitle(): string|Htmlable
    {
        return trans_choice('main.m9', 2);
    }

    public function getHeading(): string
    {
        return trans_choice('main.m9', 2);
    }
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make(__('form.gs'))->columns(2)->description(__('main.in1'))
                ->schema([
                    Forms\Components\TextInput::make('ix')->label(__('form.adme'))
                        ->readOnly(),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('jjk')->label(__('form.add'))
                            ->color('success')
                            ->action(function () {
                            //  $resetStars();
                            })
                        ])->verticalAlignment(VerticalAlignment::End),
                ]),
                Forms\Components\Section::make(__('form.gs'))->columns(3)
                ->schema([
                    Forms\Components\Toggle::make('smtp')->label(__('form.ase'))->tooltip('dddf')
                        ->required()->inline(false)->default(true),
                        Forms\Components\Toggle::make('smtp')->label(__('form.ase'))->tooltip('dddf')
                        ->required()->inline(false)->default(true),
                ]),
                Forms\Components\Section::make(__('form.gs'))->columns(2)->description(__('main.in1'))
                ->schema([
                    Forms\Components\Toggle::make('smtp')->label(__('form.ase'))
                        ->required()->inline(false)->default(true),
                    Forms\Components\Select::make('log')->label(__('form.lgd')),
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
