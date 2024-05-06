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
        return [
            Actions\Action::make('rrtt')->label(__('form.add'))->form([
                Forms\Components\Select::make('prov')->label(__('main.m16'))->required()
                    ->options(\App\Models\Prov::all()->pluck('name', 'id'))->preload()->live(),
                Forms\Components\Select::make('cou')->required()
                    ->options(function (Get $get) {
                        return \App\Models\Course::where('pub', true)->where('prov', $get('prov'))->doesntHave('users1')
                            ->pluck('name', 'id');
                    })->label('Certifications')->multiple()->preload(),
            ])->action(function ($data) {
                $ix = cache()->rememberForever('settings', function () {
                    return \App\Models\Info::findOrFail(1);
                });
                foreach ($data['cou'] as $cer) {
                    $rec = Course::findorFail($cer);
                    $rec->users()->attach(auth()->id());
                    $ma = new SMail();
                    $us = User::where('ex', 0)->first();
                    $ma->sub = 'New Certification Request';
                    $ma->from = auth()->id();
                    $ma->content = 'Hello, <br><br> I hereby request to join the <b>'.$rec->name.'</b> certification. <br><br>'.
                        'Thanks in advance. <br> <i>'.auth()->user()->name.'</i>';
                    $ma->save();
                    $ma->users2()->attach($us->id);
                    Notification::make()->success()->title(__('form.e3', ['name' => $rec->name]))->send();
                    if ($ix->smtp) {
                        try {
                            //   Notif::send($us, new NewMail($ma->sub,[auth()->user()->name,auth()->user()->email,$rec->name],'5'));
                            SendEmail::dispatch($us, $ma->sub, [auth()->user()->name, auth()->user()->email, $rec->name], '5');
                            $ma->users2()->updateExistingPivot($us->id, ['sent' => true, 'last_sent' => now()]);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(__('form.e4'))
                                ->danger()
                                ->send();
                        }
                    }
                }
            })
                ->after(function ($data) {
                    foreach ($data['cou'] as $cer) {
                        $rec = Course::findorFail($cer);
                        $txt = "Request to join '$rec->name' ";
                        \App\Models\Journ::add(auth()->user(), 'Portfolio', 8, $txt);
                    }
                })->color('success')->modalHeading(__('main.lc2'))
                ->modalSubmitActionLabel(__('form.rj')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Course::has('users1')->with('users1')->withCount('modules')->withCount('questions')->where('pub', true))
            ->emptyStateHeading(__('main.lc3'))->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateDescription(__('main.lc4'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label(__('form.na'))
                    ->description(fn (Course $record): ?string => $record->descr),
                Tables\Columns\TextColumn::make('modules_count')->sortable()->label('Modules'),
                Tables\Columns\TextColumn::make('questions_count')->sortable()->label('Questions'),
                Tables\Columns\TextColumn::make('oo')->badge()->label(__('form.ins'))
                    ->state(fn (Course $record) => $record->users1()->first()->pivot->approve ? __('form.yes') : __('form.pen'))
                    ->color(fn (Course $record) => $record->users1()->first()->pivot->approve ? 'success' : 'warning'),
            ])
            ->filters([
                Tables\Filters\Filter::make('oi')->label(__('form.ins'))
                    ->form([
                        Forms\Components\Select::make('zzz')->label(__('form.ins'))
                            ->options([
                                '0' => __('form.pen'),
                                '1' => __('form.join'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['zzz'] == '0',
                                fn (Builder $query): Builder => $query->whereRelation('users1', 'approve', false),
                            )->when(
                                $data['zzz'] == '1',
                                fn (Builder $query): Builder => $query->whereRelation('users1', 'approve', true),
                            );
                    })->indicateUsing(function (array $data): ?string {
                        if ($data['zzz'] == null) {
                            return null;
                        }

                        return match ($data['zzz']) {
                            '0' => __('main.lc5'), '1' => __('main.lc6')
                        };
                    }),
            ])->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label(__('form.fil')),
            )
            ->actions([
                Tables\Actions\DeleteAction::make()->label(__('form.rm'))->after(function ($record) {
                    $txt = "Removed '$record->name' certification from his/her portfolio.";
                    \App\Models\Journ::add(auth()->user(), 'Portfolio', 8, $txt);
                })
                    ->action(function ($record) {
                        $record->users1()->detach(auth()->id());
                    })
                    ->modalHeading(__('form.rm2', ['name' => 'certifications']))
                    ->modalDescription(fn (Course $record): string => __('main.lc7', ['name' => $record->name])),
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
