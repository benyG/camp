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
                    ->unique(table: \App\Models\Answer::class),
                Forms\Components\Toggle::make('isok')->label('Is an answer ?')
                             ->required(),
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
              //  Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect()->createOptionForm([
                    Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(200)
                    ->unique()
                     ])->createOptionUsing(function($data){
                        $tag = new Answer();
                        $tag->fill($data);
                        $tag->save();
                        return $tag->getKey();
                    }),
                    Forms\Components\Toggle::make('isok')->label('Is an answer ?')->required(),
                ])->disabled($this->getOwnerRecord()->answers()->count()>=$this->getOwnerRecord()->maxr)
                ->color('warning'),
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
