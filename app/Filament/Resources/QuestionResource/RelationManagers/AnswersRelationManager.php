<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;
use App\Models\Answer;
use Filament\Forms\Components\Actions;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;

class AnswersRelationManager extends RelationManager
{

    protected static string $relationship = 'answers';
    public function isReadOnly(): bool
    {
        return false;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(500)
                    ->unique(table: \App\Models\Answer::class,ignoreRecord: true)
                    ->validationMessages([
                        'unique' => "Answer already in our database. You may attach it using the Attach button above.",
                    ])
                    ->hintAction(
                        \Filament\Forms\Components\Actions\Action::make('kkj')->label('Attach')
                            ->icon('heroicon-m-link')
                            ->action(function (Get $get, $state) {
                              if(empty($get('text'))) Notification::make('sgg')->danger()->title('Field empty')->send();
                              else{
                                if(!Answer::where('text',$get('text'))->exists()) Notification::make('e7')->danger()->title('Answer not in the database. You may create it using the Create button')->send();
                                else {
                                    $ans=Answer::where('text',$get('text'))->first();
                                    $quest=$this->getOwnerRecord();
                                    if($quest->answers()->get()->contains($ans)) Notification::make('er')->danger()->title('Answer already attached to this question')->send();
                                    else if ($quest->answers()->count()>=$quest->maxr) {
                                        Notification::make('74')->danger()->title('Maximum answers for this question reached')->send();
                                    }
                                    else {
                                        $this->getOwnerRecord()->answers()->attach($ans->id,['isok'=>$get('isok')]);
                                        Notification::make('ed')->success()->title('Answer successfully attached')->send();
                                        $txt="Attaching
                                        Answer : $ans->text <br>
                                        to Question : ".$quest->text2;
                                        \App\Models\Journ::add(auth()->user(),'Questions',6,$txt);
                                    }
                                }
                              }
                            })
                    ),
                Forms\Components\Toggle::make('isok')->label('Is a true answer ?')->inline(false)
                             ->required()->live(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table->striped()
            ->recordTitleAttribute('text')
            ->columns([
                Tables\Columns\TextColumn::make('text'),
                Tables\Columns\IconColumn::make('isok')->boolean()->label('Answer ?')
                ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->before(function (Tables\Actions\CreateAction $action) {
                    $quest=$this->getOwnerRecord();
                    if ($quest->answers()->count()>=$quest->maxr) {
                        Notification::make('qsd')->danger()->title('Maximum answers for this question reached')->send();
                         $action->halt();
                    }
                })
                ->after(function ($data) {
                    $txt="New answer created ! <br>
                    Text: ".$data['text']." <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Answers',1,$txt);
                })
                ->label('Add Answer')
                ->disabled($this->getOwnerRecord()->answers()->count()>=$this->getOwnerRecord()->maxr),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco=$record->replicate();
                    $record->update($data);
                    $record->questions()->updateExistingPivot($this->getOwnerRecord()->id, ['isok' => $data['isok']]);
                  //  $record->
                    $txt="";
                    if($record->wasChanged()){
                        if($record->wasChanged('text')){
                            $txt.="Answer was changed from '$reco->text' <br>to '$record->text' <br>";
                        }
                       if(strlen($txt)>0) \App\Models\Journ::add(auth()->user(),'Answers',3,$txt);
                    }
                    return $record;
                }),
                Tables\Actions\DetachAction::make()->after(function ($action,$record) {
                    $txt="Detaching
                    Answer : ".$record->text." <br>
                    from Question : ".$this->getOwnerRecord()->text2;
                    \App\Models\Journ::add(auth()->user(),'Questions',7,$txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt="Detaching
                            Answer : ".$value->text." <br>
                            from Question : ".$this->getOwnerRecord()->text2;
                            \App\Models\Journ::add(auth()->user(),'Answers',7,$txt);
                                }
                     }),
                ]),
            ]);
    }
}
