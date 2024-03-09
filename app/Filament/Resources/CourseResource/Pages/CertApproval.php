<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\UsersCourse;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification as Notif;
use App\Notifications\NewMail;
use Filament\Notifications\Notification;
use App\Models\SMail;
use App\Jobs\SendEmail;

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
        ->query(UsersCourse::with('userRel')->with('courseRel')->where('approve',false))
        ->emptyStateHeading('No certification request yet')->emptyStateIcon('heroicon-o-bookmark')
        ->columns([
            Tables\Columns\TextColumn::make('userRel.name')->sortable()->searchable()->label('User')
            ->description(fn (UsersCourse $record): ?string => $record->userRel->email),
            Tables\Columns\TextColumn::make('courseRel.name')->sortable()->searchable()->label('Certification'),
            Tables\Columns\TextColumn::make('created_at')->datetime()->sortable()->label('Request Date'),
            ])
            ->filters([

            ]) ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\Action::make('resend')->label('Approve')
                ->after(function ($record) {
                    $txt="Certification Request Approved
                    User: ".$record->userRel->name."<br>
                    Certification: ".$record->courseRel->name."
                    ";
                    \App\Models\Journ::add(auth()->user(),'Cert. Approval',6,$txt);
                })
                ->action(function (UsersCourse $record) {
                    $usc=$record->userRel;
                    $record->approve=true;$record->save();
                    Notification::make()->success()->title('Request approved.')->send();
                    $ma=new SMail();
                    $ma->from=auth()->id();
                    $ma->sub='Certification Request Approved';
                    $ma->content='We\'re glad to announce you that your request for joining <b><span style="color: #15803d">'.$record->courseRel->name.'</span></b> certification has been approved. <br>'.
                        'It is now available in the Bootcamp. <i>Rush there and start testing your knowledge !</i> ';
                        $ma->save();
                        $ma->users2()->attach($usc->id);
                    if(\App\Models\Info::first()->smtp){
                        try {
                          //  Notif::send(User::findorFail($usc->id), new NewMail($ma->sub,[$usc->name],'6'));
                          SendEmail::dispatch(User::findorFail($usc->id),$ma->sub,[$usc->name],'6');
                            $ma->users2()->updateExistingPivot($usc->id, ['sent' => true,'last_sent' => now()]);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title('Error occured.')
                                ->danger()
                                ->send();
                        }
                    }
                })->button()->color('success'),
                Tables\Actions\DeleteAction::make()->iconButton()
                ->after(function ($record) {
                    $txt="Certification Request deleted
                    User: ".$record->userRel->name ."<br>
                    Certification: ".$record->courseRel->name."
                    ";
                    \App\Models\Journ::add(auth()->user(),'Cert. Approval',7,$txt);
                }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt="Certification Request deleted
                            User: ".$value->userRel->name ."<br>
                            Certification: ".$value->courseRel->name."
                            ";
                            \App\Models\Journ::add(auth()->user(),'Cert. Approval',7,$txt);
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
