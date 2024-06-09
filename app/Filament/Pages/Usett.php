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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Component;

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
                    Forms\Components\Placeholder::make('iac4')->label(__('form.iac'))
                    ->content(fn (): int => auth()->user()->ix+auth()->user()->ix2)->key('action_test_2')
                    ->hintAction(
                        \Filament\Forms\Components\Actions\Action::make('eers')->label(__('form.add'))
                        ->closeModalByClickingAway(false)
                        ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing2', ['ix' => $ix]))
                        ->color('primary')->closeModalByClickingAway(false)
                        ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge)
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->icon('heroicon-m-plus')
                    ),
                    Forms\Components\Placeholder::make('eca')->label(__('form.eca2'))->key('action_test_3')
                    ->content(fn (): int => auth()->user()->eca)
                    ->hintAction(
                        \Filament\Forms\Components\Actions\Action::make('ee0rs')->label(__('form.add'))
                        ->closeModalByClickingAway(false)
                        ->modalContent(fn (): \Illuminate\Contracts\View\View => view('components.pricing3', ['ix' => $ix]))
                        ->color('primary')->closeModalByClickingAway(false)
                        ->modalWidth(\Filament\Support\Enums\MaxWidth::Small)
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->icon('heroicon-m-plus')
                    ),
                ]),
                Forms\Components\Section::make(__('form.pa2'))->columns(2)->description(__('form.pa3'))->columns(2)
                ->schema([
                    Forms\Components\Select::make('pa1')->label(__('form.pa4'))->live()
                    ->options(auth()->user()->courses->pluck('name','id'))->selectablePlaceholder(false)
                    ->suffixAction(
                        \Filament\Forms\Components\Actions\Action::make('e03rs')->label(function(Get $get){
                            if(is_null($get('pa1')))
                            return __('form.add');
                            else
                            {
                                return auth()->user()->courses->where('id',intval($get('pa1')))->first()->pivot->approve?__('form.add'):__('form.rm');}
                        })
                        ->color('warning')
                        ->icon(function(Get $get){
                            if(is_null($get('pa1')))
                            return 'heroicon-m-plus';
                            else
                            {
                                return auth()->user()->courses->where('id',intval($get('pa1')))->first()->pivot->approve?'heroicon-m-minus':'heroicon-m-plus';}
                        })->iconButton()
                        ->action(function(Set $set, $state){
                            if(!is_null($state)){
                                $ape=!auth()->user()->courses->where('id',intval($state))->first()->pivot->approve;
                                auth()->user()->courses()->updateExistingPivot(intval($state), [
                                    'approve' => $ape,
                                ]);
                                Notification::make()->title($ape?__('form.e29'):__('form.e35'))->success()->send();
                                $this->js('window.location.reload()');
                            }else Notification::make()->title(__('form.e36'))->danger()->send();
                        })
                    ),
                    Forms\Components\Placeholder::make('dd2')->label(__('form.in'))
                    ->content(fn (): string => auth()->user()->courses->filter(function(\App\Models\Course $rec, int $ket){return $rec->pivot->approve;})->pluck('name','id')->join(', ')),
                ]),
                Forms\Components\Section::make(__('form.gs'))->columns(3)
                ->schema([
                    Forms\Components\Toggle::make('aca')->label(__('form.aeq'))->tooltip(__('form.aeq2'))
                        ->default(auth()->user()->aqa)->inline(false),
                    $this->getITG(),
                    $this->getVO(),
                ]),
      ])->statePath('data');
    }

    public function create(): void
    {
        $dt=$this->form->getState();
       // dd($dt);
        $user = auth()->user();
        $user->vo=$dt['vo'];$user->aqa=$dt['aca'];$user->itg=$dt['itg'];
        $user->save();
        Notification::make()->title(__('form.e30'))->success()->send();
        if (auth()->user()->wasChanged()) {
            \App\Models\Journ::add(auth()->user(), 'Settings', 3, 'User changed his settings');
        }
    }

    public static function canAccess(): bool
    {
        return collect(['2','3','4','5'])->contains(auth()->user()->ex);
    }
    protected function getVO(): Component
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        return auth()->user()->can('vo')?Forms\Components\Toggle::make('vo')->label(__('form.sta2'))->inline(false)->tooltip(__('form.sta3'))
        ->default(auth()->user()->vo):
            Forms\Components\Toggle::make('vo')->label(__('form.sta2'))->inline(false)->tooltip(__('form.sta3'))->disabled(true)
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

        return auth()->user()->can('tga')?Forms\Components\Toggle::make('itg')->label(__('form.tga2'))->inline(false)->tooltip(__('form.tga3'))
        ->default(auth()->user()->itg):
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