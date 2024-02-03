<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification as Notif;
use App\Notifications\NewMail;
use Filament\Notifications\Notification;
use App\Models\SMail;

class CertApproval extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = CourseResource::class;
    protected static ?string $title = 'Certifications Approvals';
    protected ?string $heading = 'Pending certifications approvals';
    protected ?string $subheading = 'Here is a list of certifications that are waiting for your approval.';
    protected static string $view = 'filament.resources.course-resource.pages.cert-approval';

    public function mount(): void
    {
        abort_unless(auth()->user()->ex==0, 403);
        static::authorizeResourceAccess();
    }
    public function table(Table $table): Table
    {
        return $table
        ->query(Course::has('users2')->with('users2')->where('pub',true))
        ->emptyStateHeading('No certification request yet')->emptyStateIcon('heroicon-o-bookmark')
        ->columns([
            Tables\Columns\TextColumn::make('users2.name')->sortable()->searchable()->label('User')
            ->description(fn (Course $record): ?string => $record->email),
            Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Certification'),
            Tables\Columns\TextColumn::make('users2.created_at')->datetime()->sortable()->label('Request Date'),
            ])
            ->filters([

            ]) ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\Action::make('resend')->label('Approve')
                ->action(function (Course $record) {
                    $usc=$record->users2()->first();
                    $record->users()->updateExistingPivot($usc->id, ['approve' => true]);
                    Notification::make()->success()->title('Request approved.')->send();
                    $ma=new SMail();
                    $ma->from=auth()->id();
                    $ma->sub='Certification Request Approved';
                    $ma->content='We\'re glad to announce you that your request for joining <b><span style="color: #15803d">'.$usc->name.'</span></b> certification has been approved. <br>'.
                        'It is now available in the Bootcamp. <i>Rush there and start testing your knowledge !</i> ';
                        $ma->save();
                        $ma->users2()->attach($usc->id);
                    if(\App\Models\Info::first()->smtp){
                        try {
                            Notif::send(User::findorFail($usc->id), new NewMail($ma->sub,[$usc->name],'6'));
                            $ma->users2()->updateExistingPivot($usc->id, ['sent' => true,'last_sent' => now()]);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title('Error occured.')
                                ->danger()
                                ->send();
                        }
                    }
                })->button()->color('success'),
                Tables\Actions\Action::make('dell')->label('Delete')->iconButton()->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Delete approval')
                ->modalDescription(fn(Course $record):string=>'Are you sure you want to do that?')
                ->action(function (Course $record) {
                    $record->users2()->detach($record->users2()->first()->id);
                })->color('danger'),

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
