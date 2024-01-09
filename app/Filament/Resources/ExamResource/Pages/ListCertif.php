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
    public function table(Table $table): Table
    {
        return $table
        ->query(Course::where('pub',true))->emptyStateHeading('No certification yet')->emptyStateIcon('heroicon-o-bookmark')
        ->emptyStateDescription('Please come later to check if there are new ones available.')
        ->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Name')
            ->description(fn (Course $record): ?string => $record->descr),
            Tables\Columns\TextColumn::make('modules_count')->counts('modules')->sortable()->label('Modules'),
            Tables\Columns\TextColumn::make('questions_count')->counts('questions')->sortable()->label('Questions'),
            Tables\Columns\IconColumn::make('users_exists')->boolean()->label('Joined')->sortable()
                ->exists([
                    'users' => fn (Builder $query) => $query->where('user', auth()->user()->id),
                ]),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('eee')
                ->label('Filter Certif.')
                ->placeholder('All')
                ->trueLabel('Joined Certif.')
                ->falseLabel('Not yet joined')
                ->queries(
                    true: fn (Builder $query) => $query->has('users1'),
                    false: fn (Builder $query) => $query->doesntHave('users1'),
                    blank: fn (Builder $query) => $query,
                )
            ]) ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\Action::make('resend')->label('Request to join')
                ->action(function (Course $record) {
                         Notif::send(User::where('ex',0)->first(), new NewMail('New Certification Request',[auth()->user()->name,auth()->user()->email,$record->name],'5'));
                        Notification::make()->success()->title('Request for joining \''.$record->name.'\' was sent to the administrators. Please wait for the reply.')->send();
                   try {
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Error occured.')
                            ->danger()
                            ->send();
                    }
                })->button()->color('success')->hidden(fn(Course $record):bool=>$record->users1()->count()>0),
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
