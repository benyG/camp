<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmailResource\Pages;
use App\Filament\Resources\SmailResource\RelationManagers;
use App\Models\SMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmailResource extends Resource
{
    protected static ?string $model = SMail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $modelLabel = 'mail';
    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $slug = 'mails';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sub')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sub')
                ->searchable()->sortable(),
             //   Tables\Columns\TextColumn::make('moduleRel.name')->label('Modules')->sortable(),
             //   Tables\Columns\TextColumn::make('answers_count')->counts('answers')->label('Answers')
             //   ->numeric()->sortable(),
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
            'index' => Pages\ManageSmails::route('/'),
        ];
    }
}
