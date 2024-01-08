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

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Teachers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cours')->label('Certifications')
                ->options(Course::all()->pluck('name', 'id'))
                ->preload()->live(),
                Forms\Components\Select::make('module')->label('Modules')->required()
                ->relationship(name: 'moduleRel', titleAttribute: 'name',
                modifyQueryUsing: function (Builder $query,Get $get,string $operation) {
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
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->latest())
            ->columns([
                Tables\Columns\TextColumn::make('text')->limit(100)->html()
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
                Tables\Filters\SelectFilter::make('module')
                    ->relationship(name: 'moduleRel', titleAttribute: 'name')
                    ->searchable()->label('Modules')->multiple()
                    ->preload()
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
