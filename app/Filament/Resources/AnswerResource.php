<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnswerResource\Pages;
use App\Filament\Resources\AnswerResource\RelationManagers;
use App\Models\Answer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnswerResource\RelationManagers\QuestionsRelationManager;
use Illuminate\Support\Facades\Route;

class AnswerResource extends Resource
{
    protected static ?string $model = Answer::class;
    protected static ?int $navigationSort = 40;
    protected static ?string $navigationGroup = 'Teachers';

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('text')
                    ->required()
                    ->maxLength(200)
                    ->unique()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()->paginated([25,50, 70,100,250, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('text')
                ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Using Questions')
                ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco=$record->replicate();
                    $record->update($data);
                    $txt="";
                    if($record->wasChanged()){
                        if($record->wasChanged('text')){
                            $txt.="Answer was changed from '$reco->text' <br>to '$record->text' <br>";
                        }
                       if(strlen($txt)>0) \App\Models\Journ::add(auth()->user(),'Answers',3,$txt);
                    }
                    return $record;
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    $txt="Removed answer ID $record->id.
                    Text: $record->text <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Answers',4,$txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                       foreach ($record as $value) {
                         $txt="Removed answer ID $value->id
                        Text: $value->text";
                        \App\Models\Journ::add(auth()->user(),'Answers',4,$txt);
                       }
                    }),
                ]),
            ])
            ->deferLoading()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }
    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAnswers::route('/'),
            'view' => Pages\ViewAnswer::route('/ans-{record}'),
        ];
    }
}
