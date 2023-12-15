<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers\AnswersRelationManager;
use App\Models\Question;
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

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TinyEditor::make('text')
                    ->required()
                    ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                    ->columnSpanFull(),
                Forms\Components\Select::make('module')->label('Modules')
                ->relationship(name: 'moduleRel', titleAttribute: 'name'),
           //     Forms\Components\Toggle::make('isexam')
           //         ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')->limit(50)->html()
                ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('moduleRel.name')->label('Modules')->sortable(),
                Tables\Columns\TextColumn::make('answers_count')->counts('answers')->label('Answers')
                ->numeric()->sortable(),
                        //     Tables\Columns\IconColumn::make('isexam')
            //        ->boolean(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['text'] = str_replace('src="../storage','src="'.env('APP_URL').'/storage',$data['text']);
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading();;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageQuestions::route('/'),
            'view' => Pages\ViewQuestion::route('/{record}'),
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
            Infolists\Components\TextEntry::make('text')->html(),
            Infolists\Components\TextEntry::make('moduleRel.name')->label('Modules'),
        ]);
}
}
