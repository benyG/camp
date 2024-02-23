<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnResource\Pages;
use App\Filament\Resources\AnnResource\RelationManagers;
use App\Models\Ann;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class AnnResource extends Resource
{
    protected static ?string $model = Ann::class;
    protected static ?int $navigationSort = 65;
    protected static ?string $navigationIcon = 'heroicon-o-signal';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'announcement';
    protected static ?string $slug = 'announcements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descr')->label('Description')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('url')->label('Clickable url')
                    ->maxLength(500)->url(),
                Forms\Components\Section::make('')->columns(3)
                ->schema([
                    Forms\Components\Select::make('type')->label('User type')
                        ->required()->multiple()
                        ->options(['1' => 'Admin','2' => 'Starter','3' => 'User','4' => 'Pro','5' => 'VIP']),
                    Forms\Components\DatePicker::make('due')->label('Due Date')->minDate(now()),
                    Forms\Components\Toggle::make('hid')->label('Display ?')
                        ->required()->inline(false)->default(true),
                ]),
                TinyEditor::make('text')
                    ->required()
                    ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->latest())
            ->columns([
                Tables\Columns\TextColumn::make('descr')
                    ->searchable(),
                Tables\Columns\IconColumn::make('hid')->label('Display')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('url')
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due')->label('Due Date')
                    ->dateTime()->since()->sortable(),
                Tables\Columns\TextColumn::make('type')->label('Users')->sortable()
                ->formatStateUsing(function($state):string{
                    $arrs=array('1','2','3','4','5');
                    $arru=array('Admin','Starter','User','Pro','VIP');
                    return str_replace($arrs,$arru,$state);
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->since()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageAnns::route('/'),
        ];
    }
}
