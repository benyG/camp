<?php

namespace App\Filament\Pages;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Forms\Components\IAUnit;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Usett extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.usett';

    public ?array $data = [];

    protected static ?int $navigationSort = 500;

    protected static ?string $navigationGroup = 'Administration';

    public function mount(): void
    {
        $this->form->fill();
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
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return $form
            ->schema([
                Forms\Components\Section::make()->columns(3)
                    ->schema([
                        Forms\Components\Placeholder::make('email')
                            ->content(fn (): string => auth()->user()->email),
                        IAUnit::make('iac4')->label(__('form.iac'))->hintAction(
                            \Filament\Forms\Components\Actions\Action::make('eers')->label(__('form.add'))
                                ->closeModalByClickingAway(false)->iconButton()
                                ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing2', ['ix' => $ix]))
                                ->color('primary')->closeModalByClickingAway(false)
                                ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge)
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false)
                                ->icon('heroicon-m-plus-circle')
                                )
                        ->content(fn (): int => auth()->user()->ix + auth()->user()->ix2)->key('action_est_2'),
                        IAUnit::make('eca')->label(__('form.eca2'))->key('action_test_3')
                            ->content(fn (): int => auth()->user()->eca)
                            ->hintAction(
                                \Filament\Forms\Components\Actions\Action::make('ee0rs')->label(__('form.add'))
                                    ->closeModalByClickingAway(false)->iconButton()
                                    ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing3', ['ix' => $ix]))
                                    ->color('primary')->closeModalByClickingAway(false)
                                    ->modalWidth(\Filament\Support\Enums\MaxWidth::Small)
                                    ->modalSubmitAction(false)
                                    ->modalCancelAction(false)
                                    ->icon('heroicon-m-plus-circle')
                            ),
                    ]),
                Forms\Components\Section::make(__('form.pa2'))->columns(2)->description(__('form.pa3'))->columns(2)
                    ->schema([
                        Forms\Components\Select::make('certs')->label(__('form.pa4'))->multiple()
                        ->default(auth()->user()->certs)
                            ->options(auth()->user()->courses->pluck('name', 'id'))
                    ]),
                Forms\Components\Section::make(__('form.gs'))->columns([
                    'sm' => 2,
                    'md' => 3,
                ])
                    ->schema([
                        Forms\Components\Toggle::make('aca')->label(__('form.aeq'))->tooltip(__('form.aeq2'))
                            ->default(auth()->user()->aqa)->inline(false),
                        $this->getITG(),
                        $this->getVO(),
                        $this->getVO2(),
                    ]),
            ])->statePath('data');
    }

    public function create(): void
    {
        $dt = $this->form->getState();
         //dd($dt);
        $user = auth()->user();
        $user->certs = $dt['certs'];
        $user->vo = $dt['vo'];
        $user->vo2 = $dt['vo2'];
        $user->aqa = $dt['aca'];
        $user->itg = $dt['itg'];
        $user->save();
        Notification::make()->title(__('form.e30'))->success()->send();
        if (auth()->user()->wasChanged()) {
            \App\Models\Journ::add(auth()->user(), 'Settings', 3, 'User changed his settings');
        }
    }

    public static function canAccess(): bool
    {
        return collect(['2', '3', '4', '5','9'])->contains(auth()->user()->ex);
    }

    protected function getVO(): Component
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return auth()->user()->can('vo') ? Forms\Components\Toggle::make('vo')->label(__('form.sta2'))->inline(false)->tooltip(__('form.sta3'))
            ->default(auth()->user()->vo) :
            Forms\Components\Toggle::make('vo')->label(__('form.sta2'))->inline(false)->tooltip(__('form.sta3'))->disabled(true)
                ->hintAction(
                    \Filament\Forms\Components\Actions\Action::make('c12')->label(__('form.upg'))
                        ->closeModalByClickingAway(false)
                        ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing1', ['ix' => $ix]))
                        ->color('primary')->closeModalByClickingAway(false)
                        ->modalSubmitAction(false)->modalCancelAction(false)
                )->default(false)->declined();
    }

    protected function getVO2(): Component
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return auth()->user()->can('vo') ? Forms\Components\Select::make('vo2')->label(__('form.aivo'))->tooltip(__('form.sta3'))
        ->options(['0'=>'Coach Ben','1'=>'Coach Becky'])->selectablePlaceholder(false)->default(auth()->user()->vo2) :
            Forms\Components\Toggle::make('vo2')->label(__('form.aivo'))->inline(false)->tooltip(__('form.sta3'))->disabled(true)->declined()
                ->hintAction(
                    \Filament\Forms\Components\Actions\Action::make('c12')->label(__('form.upg'))
                        ->closeModalByClickingAway(false)
                        ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing1', ['ix' => $ix]))
                        ->color('primary')->closeModalByClickingAway(false)
                        ->modalSubmitAction(false)->modalCancelAction(false)
                )->default(false)->declined();
    }

    protected function getITG(): Component
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return auth()->user()->can('tga') ? Forms\Components\Toggle::make('itg')->label(__('form.tga2'))->inline(false)->tooltip(__('form.tga3'))
            ->default(auth()->user()->itg) :
        Forms\Components\Toggle::make('itg')->label(__('form.tga2'))->inline(false)->tooltip(__('form.tga3'))->disabled(true)
            ->hintAction(
                \Filament\Forms\Components\Actions\Action::make('c12')->label(__('form.upg'))
                    ->closeModalByClickingAway(false)
                    ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing1', ['ix' => $ix]))
                    ->color('primary')->closeModalByClickingAway(false)
                    ->modalSubmitAction(false)->modalCancelAction(false)
            )->default(false)->declined();
    }
}
