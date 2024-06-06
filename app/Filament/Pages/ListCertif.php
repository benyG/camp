<?php

namespace App\Filament\Pages;

use App\Jobs\SendEmail;
use App\Models\Course;
use App\Models\SMail;
use App\Models\User;
use App\Notifications\NewMail;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification as Notif;
use Illuminate\Contracts\View\View;
class ListCertif extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Portfolio';

    protected static ?string $slug = 'portfolio';

    protected static string $view = 'filament.pages.list-certif';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public function mount(): void
    {
    }

    public function getSubheading(): ?string
    {
        return __('main.lc1');
    }

    protected function getHeaderActions(): array
    {
                $ix = cache()->rememberForever('settings', function () {
                    return \App\Models\Info::findOrFail(1);
                });
        return [
            Actions\Action::make('rrtt')->label(__('form.add'))->form([
                Forms\Components\Select::make('prov')->label(__('main.m16'))->required()
                    ->options(\App\Models\Prov::all()->pluck('name', 'id'))->preload()->live(),
                Forms\Components\Select::make('cou')->required()
                    ->options(function (Get $get) {
                        return \App\Models\Course::where('pub', true)->where('prov', $get('prov'))->doesntHave('users1')
                            ->pluck('name', 'id');
                    })->label('Certifications')->preload(),
            ])->action(function ($data) {
                if(auth()->user()->can('add-course',\App\Models\Course::class)){
                    $rec = Course::findorFail($data['cou']);
                    $rec->users()->attach(auth()->id());
                    Notification::make()->success()->title(__('form.e31', ['name' => $rec->name]))->send();
                }
            })->closeModalByClickingAway(false)
            ->modalDescription(__('form.e32'))
                ->after(function ($data) {
                    if(auth()->user()->can('add-course',\App\Models\Course::class)){
                            $rec = Course::findorFail($data['cou']);
                            $txt = "Certification '$rec->name' added";
                            \App\Models\Journ::add(auth()->user(), 'Portfolio', 8, $txt);
                    }
                })->color('success')->modalHeading(__('main.lc2'))
                ->modalSubmitActionLabel(__('form.add'))
                ->visible(fn():bool=>auth()->user()->can('add-course',\App\Models\Course::class)),
            Actions\Action::make('inv14')->label(__('form.add'))->closeModalByClickingAway(false)
            ->modalContent(fn (): View => view('components.pricing3', ['ix' => $ix]))
                ->color('primary')->closeModalByClickingAway(false)
                ->modalWidth(\Filament\Support\Enums\MaxWidth::Small)
                ->modalDescription(__('main.w43',['rst'=>'ECA']))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->visible(fn (): bool => auth()->user()->cannot('add-course',\App\Models\Course::class))
        ];
    }

    public function table(Table $table): Table
    {
        $eca=auth()->user()->eca-auth()->user()->courses->count();
        return $table
        ->heading(fn(Get $get)=>__('form.eca2').__('main.space').': '.($eca<0?0:$eca))
            ->query(Course::has('users1')->withCount('modules')->withCount('questions')->where('pub', true))
            ->emptyStateHeading(__('main.lc3'))->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateDescription(__('main.lc4'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label(__('form.na'))
                    ->description(fn (Course $record): ?string => $record->descr),
                Tables\Columns\TextColumn::make('modules_count')->sortable()->label('Modules'),
                Tables\Columns\TextColumn::make('questions_count')->sortable()->label('Questions'),
                /* Tables\Columns\TextColumn::make('oo')->badge()->label(__('form.ins'))
                    ->state(fn (Course $record) => $record->users1()->first()->pivot->approve ? __('form.yes') : __('form.pen'))
                    ->color(fn (Course $record) => $record->users1()->first()->pivot->approve ? 'success' : 'warning'), */
           ])
            ->filters([
                Tables\Filters\SelectFilter::make('prov')
                    ->relationship(name: 'provRel', titleAttribute: 'name')
                    ->searchable()->label(__('main.m16'))->multiple()
                    ->preload(),
            ])->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label(__('form.fil')),
            )
            ->actions([
            ])
            ->bulkActions([
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
