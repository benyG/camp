<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmailResource\Pages;
use App\Jobs\SendEmail;
use App\Models\Info;
use App\Models\SMail;
use App\Models\User;
use App\Models\Vague;
use App\Notifications\NewMail;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification as Notif;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class SmailResource extends Resource
{
    protected static ?string $model = SMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'message';

    protected static ?string $slug = 'messages';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user')->label(__('form.to'))
                    ->multiple()
                    ->required(fn (): bool => auth()->user()->ex < 2)
                    ->relationship(name: 'users', titleAttribute: 'name')
                    ->options(function () {
                        $vagues = Vague::with('users')->get();
                        $users = User::doesntHave('vagues')->where('id', '<>', auth()->user()->id)->get();
                        $bg = [];
                        $er = [];
                        foreach ($users as $uy) {
                            $er[$uy->id] = $uy->name;
                        }
                        $bg['N/A'] = $er;
                        foreach ($vagues as $vague) {
                            $ez = $vague->users;
                            $ee = [];
                            foreach ($ez as $us) {
                                $ee[$us->id] = $us->name;
                            }
                            $bg[$vague->name] = $ee;
                        }

                        return $bg;
                    })
                    ->preload()->hidden(fn (): bool => auth()->user()->ex >= 2),
                Forms\Components\TextInput::make('sub')->label(__('form.sub'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('from')
                    ->hidden(),
                TinyEditor::make('content')->label(__('form.cnt'))
                    ->required()
                    ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()->paginated([10, 25, 50, 100, 200, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('sub')->label(__('form.sub'))
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('from')->label('Correspondants')
                    ->formatStateUsing(fn (Model $record): string => $record->from == auth()->id() ? Str::remove(['"', '[', ']'], $record->users2->pluck('name')) : $record->user1->name)
                    ->sortable()->hidden(fn (): bool => auth()->user()->ex >= 2),
                Tables\Columns\IconColumn::make('sent')->label(__('form.svs'))->hidden(fn (): bool => auth()->user()->ex >= 2)
                    ->getStateUsing(fn (Smail $record) => $record->users1->first()->pivot->sent ?? null)
                    ->icon(fn ($state): string => $state ? 'heroicon-o-envelope' : '')
                    ->tooltip(fn (Smail $record): string => $record->users1->first()->pivot->sent == 1 ? __('form.lso')." {$record->users1->first()->pivot->last_sent}" : '')
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('read')->label(__('form.read1'))
                    ->getStateUsing(fn (Smail $record) => $record->users1->first()->pivot->read ?? null)
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->icon(fn ($state, Smail $record): string => $record->from == auth()->user()->id ? '' : ($state ? 'heroicon-o-envelope-open' : 'heroicon-o-envelope'))
                    ->tooltip(fn (Smail $record): string => $record->from == auth()->user()->id ? '' : ($record->users1->first()->pivot->read ? "Read on {$record->users1->first()->pivot->read_date}" : ''))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('form.uat'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make('jjhg')->label(__('form.read'))->beforeFormFilled(function (Model $record) {
                    if ($record->from != auth()->id() && ! $record->users1->first()->pivot->read) {
                        $record->users1()->updateExistingPivot(auth()->id(), ['read' => true, 'read_date' => now()]);
                    }
                })->modalHeading(fn (Model $record): string => $record->sub),
                Tables\Actions\Action::make('transfer')->modalHeading(__('form.tr'))
                    ->icon('heroicon-o-envelope')
                    ->label(__('form.tr'))->form([
                    Forms\Components\Select::make('user4')->label('To')
                        ->multiple()->hidden(fn (): bool => auth()->user()->ex >= 2)
                        ->required(fn (): bool => auth()->user()->ex < 2)
                        ->options(function () {
                            $vagues = Vague::with('users')->get();
                            $users = User::doesntHave('vagues')->where('id', '<>', auth()->user()->id)->get();
                            $bg = [];
                            $er = [];
                            foreach ($users as $uy) {
                                $er[$uy->id] = $uy->name;
                            }
                            $bg['N/A'] = $er;
                            foreach ($vagues as $vague) {
                                $ez = $vague->users;
                                $ee = [];
                                foreach ($ez as $us) {
                                    $ee[$us->id] = $us->name;
                                }
                                $bg[$vague->name] = $ee;
                            }

                            return $bg;
                        })
                        ->preload(),
                    Forms\Components\TextInput::make('sub')->label(__('form.sub'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('from')
                        ->hidden(),
                    TinyEditor::make('content')->label(__('form.cnt'))
                        ->required()
                        ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                        ->columnSpanFull(),
                ])->fillForm(fn (Smail $record): array => [
                    'content' => $record->content,
                    'sub' => $record->sub,
                ])->action(function (array $data, Smail $record, string $model): void {
                    $data['from'] = auth()->user()->id;
                    $rec = $model::create($data);
                    $rec->users()->attach($data['user4']);
                    Notification::make()->success()->title(__('form.e26'))->send();
                    $txt = "Message ID $record->id tranfered
                        Sub: $record->sub <br>
                        To: ".implode(',', $rec->users2()->pluck('name')->toArray());
                    \App\Models\Journ::add(auth()->user(), 'Inbox', 1, $txt);
                    foreach ($rec->users2 as $us) {
                        try {
                            //   Notif::send($us, new NewMail($record->sub,$para,$opt));
                            SendEmail::dispatch($us, $rec->sub, $para, $opt);
                            $record->users2()->updateExistingPivot($us->id, ['sent' => true, 'last_sent' => now()]);
                            Notification::make()->success()->title(__('form.e8').$us->email)->send();
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(__('form.e7').$us->email)
                                ->danger()
                                ->send();
                        }
                    }
                })->hidden(fn (): bool => auth()->user()->ex >= 2),
                Tables\Actions\Action::make('resend')->color('warning')->label(__('form.res2'))->icon('heroicon-o-paper-airplane')
                    ->after(function ($record) {
                        $txt = "Message ID $record->id, resent via SMTP
                    Sub: $record->sub <br>
                    To: ".implode(',', $record->users2()->pluck('name')->toArray());
                        \App\Models\Journ::add(auth()->user(), 'Inbox', 8, $txt);
                    })
                    ->action(function (Smail $record) {
                        $para = [];
                        $opt = '1';
                        if (auth()->user()->ex >= 2) {
                            $para = [auth()->user()->name, auth()->user()->email];
                            $opt = '4';
                        }
                        foreach ($record->users2 as $us) {
                            try {
                                //   Notif::send($us, new NewMail($record->sub,$para,$opt));
                                SendEmail::dispatch($us, $record->sub, $para, $opt);
                                $record->users2()->updateExistingPivot($us->id, ['sent' => true, 'last_sent' => now()]);
                                Notification::make()->success()->title(__('form.e8').$us->email)->send();
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->title(__('form.e7').$us->email)
                                    ->danger()
                                    ->send();
                            }
                        }
                    })
                    ->visible(fn (Smail $record) => ($record->from == auth()->user()->id) && Info::first()->smtp),
                Tables\Actions\Action::make('Delete')->modalHeading(__('form.del1'))->label(__('form.del'))->icon('heroicon-o-trash')
                    ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-trash')->modalIconColor('warning')
                    ->after(function ($record) {
                        $txt = "Deleted message ID $record->id.
                    Sub: $record->sub";
                        \App\Models\Journ::add(auth()->user(), 'Inbox', 4, $txt);
                    })
                    ->action(function (Smail $record) {
                        if ($record->from == auth()->user()->id) {
                            $record->hid = true;
                            $record->save();
                        } else {
                            $record->users()->detach(auth()->user()->id);
                        }
                        Notification::make('e')->title(__('form.e27'))->icon('heroicon-o-trash')
                            ->iconColor('success')->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Delete')->modalHeading(__('form.del1').'s')->label(__('form.del2'))
                        ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-trash')->modalIconColor('warning')
                        ->after(function (Collection $record) {
                            foreach ($record as $value) {
                                $txt = "Deleted message ID $value->id
                         Sub: $value->sub";
                                \App\Models\Journ::add(auth()->user(), 'Inbox', 4, $txt);
                            }
                        })
                        ->action(function (Collection $record) {
                            $record->each(function (Smail $rec, int $key) {
                                if ($rec->from == auth()->user()->id) {
                                    $rec->hid = true;
                                    $rec->save();
                                } else {
                                    $rec->users()->detach(auth()->user()->id);
                                }
                            });
                            Notification::make('e')->title(__('form.e27'))->icon('heroicon-o-trash')
                                ->iconColor('success')->send();
                        })->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSmails::route('/'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')->label(__('form.frm').__('main.space').': ')
                    ->extraAttributes(['class' => 'font-bold'])
                    ->hidden(fn (Model $record): bool => $record->from == auth()->user()->id),
                Infolists\Components\Section::make(__('form.cnt'))
                    ->schema([
                        Infolists\Components\TextEntry::make('content')->label('')->html()->columnSpanFull(),
                    ]),
                Infolists\Components\TextEntry::make('users.name')->label(__('form.to'))
                    ->hidden(fn (Model $record): bool => $record->users->contains(auth()->user())),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Smail::selectRaw('user')->join('users_mail', 'smails.id', '=', 'users_mail.mail')
            ->where('read', false)->where('user', auth()->user()->id)->get()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Smail::selectRaw('user')->join('users_mail', 'smails.id', '=', 'users_mail.mail')
            ->where('read', false)->where('user', auth()->user()->id)->get()->count() > 0 ? 'danger' : 'primary';
    }
}
