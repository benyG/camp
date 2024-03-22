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
                })->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco=$record->replicate();
                    $record->update($data);
                    $txt="";
                    if($record->wasChanged()){
                        if($record->wasChanged('text')){
                            $txt.="Text was changed from '$reco->text' <br>to '$record->text' <br>";
                        }
                        if($record->wasChanged('maxr')){
                            $txt.="Max Answer was changed from '$reco->maxr' to '$record->maxr' <br>";
                        }
                        if($record->wasChanged('module')){
                            $txt.="Answer was changed from ".$reco->moduleRel->name." (".$reco->certif->name.")
                             <br>to ".$record->moduleRel->name." (".$record->certif->name.") <br>";
                        }
                        if($record->wasChanged('descr')){
                            $txt.="Explanation was changed from '$reco->descr' <br>to '$record->descr' <br>";
                        }
                       if(strlen($txt)>0) \App\Models\Journ::add(auth()->user(),'Questions',3,"Question ID $record->id was changed <br> ".$txt);
                    }
                    return $record;
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    $txt="Removed question ID $record->id.
                    Text: $record->text <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Questions',4,$txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                          $txt="Question answer ID $value->id
                         Text: $value->text";
                         \App\Models\Journ::add(auth()->user(),'Questions',4,$txt);
                        }
                     }),
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
                })->hintActions([
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
                        }),
                    \Filament\Infolists\Components\Actions\Action::make('otoi')->label('Ask the AI')//->requiresConfirmation()
                    ->icon('heroicon-m-question-mark-circle')->color('primary')
                    ->action(function () {})
                   ->modalWidth(\Filament\Support\Enums\MaxWidth::Medium)
                    ->modalCancelActionLabel('OK')
                    ->modalCancelAction(fn (\Filament\Actions\StaticAction $action) => $action->color('primary'))
                    ->modalSubmitAction(false)
                    ->modalContent(function ($record): \Illuminate\Contracts\View\View
                    {
                        $ix=cache()->rememberForever('settings', function () {return \App\Models\Info::findOrFail(1);});
                        $ik=1;
                        $aitx="";
                        foreach ($record->answers as $value) {
                            if(!is_string($value)){
                                $value=$value->text;
                            }
                           $aitx.=$ik.". ".$value."\n ";
                        }
                        $stats=$record->certif->name." certification exam:
                            - Question :
                            $record->text
                            - Answers choice :".$aitx.".";
                           $txot="";
                        try {
                            $apk=\Illuminate\Support\Facades\Crypt::decryptString($ix->apk);
                          //  dd($apk);
                            $response = \Illuminate\Support\Facades\Http::withToken($apk)->post($ix->endp, [
                                "model" => $ix->model,
                                'messages' => [
                                    ["role" => "system", "content" => str_replace("XoXo",auth()->user()->name,$ix->cont2)],
                                    ["role" => "user","content" => $stats],
                                ],
                            ])
                            ->json();
                           if(is_array($response["choices"]))   {
                               $txot=$response["choices"][0]["message"]["content"];
                               \App\Models\User::where('id',auth()->id())->update(['ix'=>auth()->user()->ix+1]);
                           }
                           else Notification::make()->danger()->title("Query error.")->send();
                       } catch (DecryptException $e) {
                            Notification::make()->danger()->title("There was a problem during encryption.")->send();
                           } catch (ConnectionException $e) {
                               Notification::make()->danger()->title("The query took too much time.")->send();
                           }
                      return  view('filament.pages.actions.iamod2',['txt' => $txot]);
                    })
                ])
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
