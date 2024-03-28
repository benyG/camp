<?php

namespace App\Filament\Resources\AnswerResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table->striped()
            ->recordTitle(fn (\App\Models\Question $record): string => $record->text2)
            ->columns([
                Tables\Columns\TextColumn::make('text2')->limit(100)->label('Question'),
                Tables\Columns\IconColumn::make('isok')->boolean()->label(__('main.an1')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordSelectSearchColumns(['text'])
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Toggle::make('isok')->label(__('main.an1'))->required(),
                    ])->color('primary')
                    ->after(function ($data) {
                        $txt = 'Attaching
                    Answer : '.$this->getOwnerRecord()->text.' <br>
                    to Question : '.\App\Models\Question::findOrFail($data['recordId'])->text2;
                        \App\Models\Journ::add(auth()->user(), 'Answers', 6, $txt);
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->after(function ($action, $record) {
                        $txt = 'Detaching
                    Answer : '.$this->getOwnerRecord()->text.' <br>
                    from Question : '.$record->text2;
                        \App\Models\Journ::add(auth()->user(), 'Answers', 7, $txt);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = 'Detaching
                            Answer : '.$this->getOwnerRecord()->text.' <br>
                            from Question : '.$value->text2;
                            \App\Models\Journ::add(auth()->user(), 'Answers', 7, $txt);
                        }
                    }),
                ]),
            ]);
    }
}
