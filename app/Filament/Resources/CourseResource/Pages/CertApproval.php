<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Jobs\SendEmail;
use App\Models\SMail;
use App\Models\User;
use App\Models\UsersCourse;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class CertApproval extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = CourseResource::class;

    protected static string $view = 'filament.resources.course-resource.pages.cert-approval';

    public function getTitle(): string|Htmlable
    {
        return __('main.m6');
    }

    public function getHeading(): string
    {
        return __('main.ca2');
    }

    public function getSubheading(): ?string
    {
        return __('main.ca3');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->ex == 0, 403);
        static::authorizeResourceAccess();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(UsersCourse::with('userRel')->with('courseRel')->where('approve', false))
            ->emptyStateHeading(__('main.ca1'))->emptyStateIcon('heroicon-o-bookmark')
            ->columns([
                Tables\Columns\TextColumn::make('userRel.name')->sortable()->searchable()->label(__('form.us'))
                    ->description(fn (UsersCourse $record): ?string => $record->userRel->email),
                Tables\Columns\TextColumn::make('courseRel.name')->sortable()->searchable()->label('Certification'),
                Tables\Columns\TextColumn::make('created_at')->datetime()->sortable()->label(__('form.red')),
            ])
            ->filters([

            ])->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label(__('form.fil')),
            )
            ->actions([
                Tables\Actions\Action::make('resend')->label(__('form.apv'))
                    ->after(function ($record) {
                        $txt = 'Certification Request Approved
                    User: '.$record->userRel->name.'<br>
                    Certification: '.$record->courseRel->name.'
                    ';
                        \App\Models\Journ::add(auth()->user(), 'Cert. Approval', 6, $txt);
                    })
                    ->action(function (UsersCourse $record) {
                        $usc = $record->userRel;
                        $record->approve = true;
                        $record->save();
                        Notification::make()->success()->title('Request approved.')->send();
                        $ma = new SMail();
                        $ma->from = auth()->id();
                        $ma->sub = 'Certification Request Approved';
                        $ma->content = 'We\'re glad to announce you that your request for joining <b><span style="color: #15803d">'.$record->courseRel->name.'</span></b> certification has been approved. <br>'.
                            'It is now available in the Bootcamp. <i>Rush there and start testing your knowledge !</i> ';
                        $ma->save();
                        $ma->users2()->attach($usc->id);
                        if (\App\Models\Info::first()->smtp) {
                            try {
                                SendEmail::dispatch(User::findorFail($usc->id), $ma->sub, [$usc->name], '6');
                                $ma->users2()->updateExistingPivot($usc->id, ['sent' => true, 'last_sent' => now()]);
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->title(__('form.e4'))
                                    ->danger()
                                    ->send();
                            }
                        }
                    })->button()->color('success'),
                Tables\Actions\DeleteAction::make()->iconButton()
                    ->after(function ($record) {
                        $txt = 'Certification Request deleted
                    User: '.$record->userRel->name.'<br>
                    Certification: '.$record->courseRel->name.'
                    ';
                        \App\Models\Journ::add(auth()->user(), 'Cert. Approval', 7, $txt);
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = 'Certification Request deleted
                            User: '.$value->userRel->name.'<br>
                            Certification: '.$value->courseRel->name.'
                            ';
                            \App\Models\Journ::add(auth()->user(), 'Cert. Approval', 7, $txt);
                        }
                    }),
                ]),
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
