<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournResource\Pages;
use App\Filament\Resources\JournResource\RelationManagers;
use App\Models\Journ;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class JournResource extends Resource
{
    protected static ?string $model = Journ::class;
    protected static ?int $navigationSort = 100;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'log';
    protected static ?string $slug = 'logs';
    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->paginated([25,50,100,250])
        ->modifyQueryUsing(fn (Builder $query) => $query->with('userRel')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('ac')->label('Action')->sortable()->badge()
                ->formatStateUsing(fn($state)=>match ($state) {
                    0 => 'Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete',_=>'N/A'
                }),
                Tables\Columns\TextColumn::make('text')->label('Details')->limit(15),
                Tables\Columns\TextColumn::make('userRel.name')->label('User')->sortable(),
                Tables\Columns\TextColumn::make('fen')->label('Page')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')->label('Users')
                ->relationship(name: 'userRel', titleAttribute: 'name')->multiple()
                ->searchable()
                ->preload(),
                Tables\Filters\SelectFilter::make('type')->label('Action')->multiple()
                ->options([0 => 'Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete'])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
/*                 Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
 */            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJourns::route('/'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('userRel.name')->label('User'),
                Infolists\Components\TextEntry::make('ac')->label('Action')->formatStateUsing(fn($state)=>match ($state) {
                    0 => 'Login',1 => 'Create',2 => 'Read',3 => 'Update',4 => 'Delete',_=>'N/A'
                })->badge(),
                Infolists\Components\TextEntry::make('fen')->label('Page'),
                Infolists\Components\TextEntry::make('created_at')->label('Date')->dateTime(),
                Infolists\Components\TextEntry::make('text')->label('Content')->html()->columnSpanFull(),
            ]);
    }
}
