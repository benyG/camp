<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VagueResource\Pages;
use App\Filament\Resources\VagueResource\RelationManagers;
use App\Models\Vague;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VagueResource extends Resource
{
    protected static ?string $model = Vague::class;
    protected static ?int $navigationSort = 80;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $modelLabel = 'Class';
    public static function form(Form $form): Form
    {
        $ix=cache()->rememberForever('settings', function () { return \App\Models\Info::findOrFail(1);});
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('users')->label('Users')->multiple()
                    ->relationship(name: 'users', titleAttribute: 'name')
                    ->preload()->maxItems(fn():int=>$ix->maxcl)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
        ->modifyQueryUsing(fn (Builder $query) => $query->withCount('users'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('users_count')->label('Students')
                    ->numeric()->sortable(),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVagues::route('/'),
        ];
    }
}
