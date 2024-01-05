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
        return $table
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
                Tables\Actions\ViewAction::make()
                ->url(fn (Answer $record) => AnswerResource::getUrl('view', ['slug' => $record->slug,'record'=>$record->id]))
                ,
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading();;
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
            'view' => Pages\ViewAnswer::route('/ans-{record}-{slug}'),
        ];
    }
    public static function getUrl(string $name = 'index', array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
            $parameters['slug'] = self::getCurrentMenu();

        return parent::getUrl($name, $parameters, $isAbsolute, $panel, $tenant);
    }
    private static function getCurrentMenu()
    {
        $menu = request('menu');
        if(isset($menu)){
            return $menu;
        }

        //try to get the Model from the previous known route
        $previousRoute = Route::getRoutes()->match(request()->create(url()->previousPath()));
        if (isset($previousRoute)) {
            //the model is not in the request (this is probably a Livewire request)
            //reset it
            $menu = $previousRoute->parameter('menu');
            request()->merge(['menu ' => $menu ]);
            return $menu;
        }

        return null;
    }
}
