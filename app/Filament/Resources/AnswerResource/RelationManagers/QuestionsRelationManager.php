<?php

namespace App\Filament\Resources\AnswerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('text')
            ->columns([
                Tables\Columns\TextColumn::make('text')->limit(100)->html()->label('Question'),
                Tables\Columns\IconColumn::make('isok')->boolean()->label('Answer ?')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\Toggle::make('isok')->label('Is an answer ?')->required(),
                ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
