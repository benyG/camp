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
                    ->unique(table: \App\Models\Answer::class)
                    ->validationMessages([
                        'unique' => "Answer already in our database. You may attach it using the Attach button above.",
                    ])
                    ->hintAction(
                        \Filament\Forms\Components\Actions\Action::make('kkj')->label('Attach')
                            ->icon('heroicon-m-link')
                            ->action(function (Get $get, $state) {
                              if(empty($get('text'))) Notification::make()->danger()->title('Field empty')->send();
                              else{
                                if(!Answer::where('text',$get('text'))->exists()) Notification::make()->danger()->title('Answer not in the database. You may create it using the Create button')->send();
                                else {
                                    $ans=Answer::where('text',$get('text'))->first();
                                    $quest=$this->getOwnerRecord();
                                    if($quest->answers()->get()->contains($ans)) Notification::make()->danger()->title('Answer already attached to this question')->send();
                                    else if ($quest->answers()->count()>=$quest->maxr) {
                                        Notification::make()->danger()->title('Maximum answers for this question reached')->send();
                                    }
                                    else {
                                        $this->getOwnerRecord()->answers()->attach($ans->id,['isok',$get('isok')]);
                                        Notification::make()->success()->title('Answer successfully attached')->send();
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
        return $table
            ->recordTitleAttribute('text')
            ->columns([
                Tables\Columns\TextColumn::make('text'),
                Tables\Columns\IconColumn::make('isok')->boolean()->label('Answer ?')
                ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->before(function (Actions\CreateAction $action, Model $record) {
                    $quest=$this->getOwnerRecord();
                    if ($quest->answers()->count()>=$quest->maxr) {
                        Notification::make()->danger()->title('Maximum answers for this question reached')->send();
                         $action->halt();
                    }
                })
                ->label('Add Answer')
                ->disabled($this->getOwnerRecord()->answers()->count()>=$this->getOwnerRecord()->maxr),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
