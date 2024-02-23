<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers\AnswersRelationManager;
use App\Models\Question;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?int $navigationSort = 30;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Teachers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cours')->label('Certifications')
                ->options(Course::all()->pluck('name', 'id'))->default(session('cours'))
                ->preload()->live(),
                Forms\Components\Select::make('module')->label('Modules')->required()
                ->relationship(name: 'moduleRel', titleAttribute: 'name',
                modifyQueryUsing: function (Builder $query, Get $get, string $operation)
                {
                    if($operation=='create') return $query->where('course',$get('cours'));
                    else{
                        if(is_numeric($get('cours'))) return $query->where('course',$get('cours'));
                        else return $query;
                    }
                }),
                Forms\Components\TextInput::make('maxr')->label('Max. Answers')
                ->required()
                ->default(4)->inputMode('numeric')
                ->rules(['numeric']),
                TinyEditor::make('text')
                    ->required()
                    ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('descr')->columnSpanFull()->label('Explanation'),

           //     Forms\Components\Toggle::make('isexam')
           //         ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->paginated([25,50,100,250])
        ->modifyQueryUsing(fn (Builder $query) => $query->with('reviews')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('text')->limit(100)->html()
                ->searchable()->sortable(),
             //   Tables\Columns\TextColumn::make('answers2_count')->label('True answers')->numeric()->sortable()
              //  ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('moduleRel.name')->label('Module')->sortable(),
                Tables\Columns\TextColumn::make('certif.name')->label('Certification')->sortable(),
                Tables\Columns\TextColumn::make('answers_count')->counts('answers')->label('Answers')
                ->numeric()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('module')
                    ->relationship(name: 'moduleRel', titleAttribute: 'name')
                    ->searchable()->label('Modules')->multiple()
                    ->preload(),
                    Tables\Filters\SelectFilter::make('certif')
                    ->relationship(name: 'certif', titleAttribute: 'name')
                    ->searchable()->label('Certifications')->multiple()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['text'] = str_replace('src="../storage','src="'.env('APP_URL').'/storage',$data['text']);
                    return $data;
                })->mutateRecordDataUsing(function (array $data, QUestion $record): array {
                    $data['cours']=$record->certif->id;
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageQuestions::route('/'),
            'view' => Pages\ViewQuestion::route('/quest-{record}'),
        ];
    }
    public static function getRelations(): array
    {
        return [
            AnswersRelationManager::class,
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('moduleRel.name')->label('Modules'),
                Infolists\Components\TextEntry::make('maxr')->label('Max. Answers'),
                Infolists\Components\TextEntry::make('text')->html(),
                Infolists\Components\TextEntry::make('descr')->html()->label('Explanation'),
                Infolists\Components\TextEntry::make('reviews.id')->html()->label('Reviews')->columnSpanFull()->placeholder('None')
                ->formatStateUsing(function ($state)
                {
                    $htm="<div class='text-sm text-gray-400 fi-in-placeholder dark:text-gray-500'>None</div>";
                    $st=\App\Models\Review::with('userRel')->whereIn('id',explode(',',$state))->get();
                   // dd($st);
                    if(!empty($state)) {
                        $htm="<ul class='list-disc list-inside'>";
                        foreach ($st as $val) {
                            $htm.="<li> User <b>".$val->userRel->name."</b> thinks that the answer to this question is <b>'".
                            implode(', ',\App\Models\Answer::whereIn('id',json_decode($val->ans))->get()->pluck('text')->toArray()).
                            "'</b></li>";
                        }
                        $htm.="</ul>";
                    }
                    return $htm;
                })->hintAction(
                    \Filament\Infolists\Components\Actions\Action::make('ooi')->label('Mark as Reviewed')->requiresConfirmation()
                        ->icon('heroicon-m-check-circle')->color('warning')
                        ->visible(fn($record):bool=> $record->reviews()->count()>0)
                        ->action(function ($record) {
                            foreach ($record->reviews as $rev) {
                                $ma = new \App\Models\SMail;
                                $ma->from=auth()->id();
                                $ma->sub="Question Reviewed !";
                                $ans="";
                                if($record->answers2()->count()==1){
                                    $ans=$record->answers2()->first()->text;
                                }else if($record->answers2()->count()<1){
                                    $ans='None';
                                }
                                else{
                                    $ans='<ul><li>'.implode('<li>',$record->answers2()->pluck('text')).'</ul>';
                                }
                                $ma->content='Dear Bootcamper , <br><br>'.
                                'On '.Carbon::parse($rev->created_at)->toDayDateTimeString().', you requested a review of this question: <br><br> <b>'.$record->text.'</b>'
                                    .'<br> We are pleased to let you know that the question was reviewed by our team, and this is the validated answer:<br><b>'.
                                    $ans.'</b>
                                    <br><br> Thank you for your contribution !<br><i>The ITExamBootCamp Team</i>';
                                $ma->save();$ma->users2()->attach($rev->user);
                            }
                            Notification::make()->success()->title('Question reviewed.')->send();
                            \App\Models\Review::destroy($record->reviews()->pluck('id'));
                            Notification::make()->success()->title('Users Notified.')->send();
                        })
                ),
            ]);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view');
    }
    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
