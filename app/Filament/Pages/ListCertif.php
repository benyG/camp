<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
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
use Filament\Actions;

class ListCertif extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $title = 'Portfolio';
    protected static ?string $slug = 'portfolio';
    protected ?string $subheading = 'Check the list of the certifications you are following. Use the button Add to request for more.';
    protected static string $view = 'filament.pages.list-certif';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 5;

    public function mount(): void
    {
    }

    protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('Add')->form([
            Forms\Components\Select::make('cou')->required()
            ->options(\App\Models\Course::where('pub',true)->doesntHave('users1')
            ->pluck('name', 'id'))->label('Certifications')->multiple()->preload(),
        ])->action(function ($data) {
                $ix=cache()->rememberForever('settings', function () {
                    return \App\Models\Info::findOrFail(1);});
            foreach ($data['cou'] as $cer) {
                $rec=Course::findorFail($cer);
                $rec->users()->attach(auth()->id());
                $ma=new SMail();
                $us=User::where('ex',0)->first();
                $ma->sub='New Certification Request';
                $ma->from=auth()->id();
                $ma->content='Hello, <br><br> I hereby request to join the <b>'.$rec->name.'</b> certification. <br><br>'.
                    'Thanks in advance. <br> <i>'.auth()->user()->name.'</i>';
                $ma->save();
                $ma->users2()->attach($us->id);
                Notification::make()->success()->title('Request for joining \''.$rec->name.'\' was sent to the administrators. Please wait for the reply.')->send();
                if($ix->smtp){
                    try {
                        Notif::send($us, new NewMail($ma->sub,[auth()->user()->name,auth()->user()->email,$rec->name],'5'));
                        $ma->users2()->updateExistingPivot($us->id, ['sent' => true,'last_sent' => now()]);
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Error occured.')
                            ->danger()
                            ->send();
                    }
                }
            }
        })->color('success')->modalHeading('Add to portfolio')
        ->modalSubmitActionLabel('Request to join')

    ];
}
    public function table(Table $table): Table
    {
        return $table
        ->query(Course::has('users1')->with('users1')->withCount('modules')->withCount('questions')->where('pub',true))
        ->emptyStateHeading('No certification yet')->emptyStateIcon('heroicon-o-bookmark')
        ->emptyStateDescription('Please click on the Add button to see the list of available certifications.')
        ->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Name')
            ->description(fn (Course $record): ?string => $record->descr),
            Tables\Columns\TextColumn::make('modules_count')->sortable()->label('Modules'),
            Tables\Columns\TextColumn::make('questions_count')->sortable()->label('Questions'),
            Tables\Columns\TextColumn::make('uexists')->badge()->label('Joined')->sortable()
            ->state(fn (Course $record) => $record->users1()->first()->pivot->approve? "Yes":"Pending")
            ->color(fn (Course $record) => $record->users1()->first()->pivot->approve? "success":"warning"),
            ])
            ->filters([
                Tables\Filters\Filter::make('oi')->label('Joined')
                ->form([
                    Forms\Components\Select::make('zzz')->label('Joined')
                    ->options([
                        '0' => 'Pending',
                        '1' => 'Joined',
                    ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['zzz']=='0',
                            fn (Builder $query): Builder => $query->whereRelation('users1', 'approve', false),
                        )->when(
                            $data['zzz']=='1',
                            fn (Builder $query): Builder => $query->whereRelation('users1', 'approve', true),
                        );
                })->indicateUsing(function (array $data): ?string {
                    if ($data['zzz']==null) {
                        return null;
                    }
                    return match ($data['zzz']) {'0' => "Cert. approval pending", '1' => "Joined Cert."};
                })
            ]) ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
            Tables\Actions\DeleteAction::make()->label('Remove')
            ->action(function ($record) {
               $record->users1()->detach(auth()->id());
                })
                ->modalHeading('Remove certifications')
                ->modalDescription(fn(Course $record):string=>'Are you sure you want to remove the \''.$record->name.'\' certification from your portfolio ?')
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
