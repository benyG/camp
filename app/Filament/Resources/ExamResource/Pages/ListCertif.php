<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
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
use Filament\Forms;
use Filament\Forms\Form;

class ListCertif extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = ExamResource::class;
    protected static ?string $title = 'Certifications';
    protected ?string $heading = 'Available certifications';
    protected ?string $subheading = 'Check the list to see which certification you can request to join for tests.';
    protected static string $view = 'filament.resources.exam-resource.pages.list-certif';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }
/*     public function CertRequest($user,$id){
        if(auth()->user()->ex==0){
            Course::findOrFail($id)->users()->
        }else abort(403);
        return back();
    } */
    public function table(Table $table): Table
    {
        return $table
        ->query(Course::selectRaw('*,approve,course,user,name,courses.id')->leftJoin('users_course', 'courses.id', '=', 'users_course.course')->where('pub',true))->emptyStateHeading('No certification yet')->emptyStateIcon('heroicon-o-bookmark')
        ->emptyStateDescription('Please come later to check if there are new ones available.')
        ->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Name')
            ->description(fn (Course $record): ?string => $record->descr),
            Tables\Columns\TextColumn::make('modules_count')->counts('modules')->sortable()->label('Modules'),
            Tables\Columns\TextColumn::make('questions_count')->counts('questions')->sortable()->label('Questions'),
            Tables\Columns\TextColumn::make('uexists')->badge()->label('Joined')->sortable()
            ->state(function (Course $record) {
                if($record->users1()->count()==0)
                return "No";
                else {
                    return $record->users1()->first()->pivot->approve? "Yes":"Pending";
                }
            })->color(function (Course $record) {
                if($record->users1()->count()==0)
                return "danger";
                else {
                    return $record->users1()->first()->pivot->approve? "success":"warning";
                }
            }),
            ])
            ->filters([
                Tables\Filters\Filter::make('oi')->label('Joined')
                ->form([
                    Forms\Components\Select::make('zzz')->label('Joined')
                    ->options([
                        '0' => 'Pending',
                        '1' => 'Not yet',
                        '2' => 'Joined',
                    ])                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['zzz']=='0',
                            fn (Builder $query): Builder => $query->where('approve',false),
                        )->when(
                            $data['zzz']=='1',
                            fn (Builder $query): Builder => $query->where('user',null),
                        )->when(
                            $data['zzz']=='2',
                            fn (Builder $query): Builder => $query->where('approve',true)->where('user',auth()->id()),
                        );
                })->indicateUsing(function (array $data): ?string {
                    if (! $data['zzz']) {
                        return null;
                    }
                    return match ($data['zzz']) {'0' => "Cert. approval pending",'1' => "Cert. not joined", '2' => "Joined Cert."};
                })
            ]) ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\Action::make('resend')->label('Request to join')
                ->action(function (Course $record) {
                    $record->users()->attach(auth()->id());
                    $ma=new SMail();
                    $us=User::where('ex',0)->first();
                    $ma->sub='New Certification Request';
                    $ma->from=auth()->id();
                    $ma->content='Hello, <br><br> I hereby request to join the <b>'.$record->name.'</b> certification. <br><br>'.
                        'Thanks in advance. <br> <i>'.auth()->user()->name.'</i>';
                    $ma->save();
                    $ma->users2()->attach($us->id);
                    Notification::make()->success()->title('Request for joining \''.$record->name.'\' was sent to the administrators. Please wait for the reply.')->send();
                    $ix=cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);});
                    if($ix->smtp){
                        try {
                            Notif::send($us, new NewMail($ma->sub,[auth()->user()->name,auth()->user()->email,$record->name],'5'));
                            $ma->users2()->updateExistingPivot($us->id, ['sent' => true,'last_sent' => now()]);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title('Error occured.')
                                ->danger()
                                ->send();
                        }
                    }
                })->button()->color('success')->hidden(fn(Course $record):bool=>$record->users1()->count()!=0),
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
