<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Filament\Resources\ModuleResource\RelationManagers;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?int $navigationSort = 50;
    protected static ?string $navigationGroup = 'Teachers';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('course')->label('Certification')->required()
                ->relationship(name: 'courseRel', titleAttribute: 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->paginated([25, 50, 100, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('courseRel.name')->label('Certification')->sortable(),
                    Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions')
                    ->numeric()->sortable(),
                   Tables\Columns\TextColumn::make('added_at')
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageModules::route('/'),
        ];
    }
    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
