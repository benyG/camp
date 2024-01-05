<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfoResource\Pages;
use App\Filament\Resources\InfoResource\RelationManagers;
use App\Models\Info;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InfoResource extends Resource
{
    protected static ?string $model = Info::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $modelLabel = 'setting';
    protected static ?string $slug = 'settings';
    protected static ?string $navigationGroup = 'Other';

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->where('id',1))
            ->columns([
                Tables\Columns\TextInputColumn::make('wperc')->label('Win Perc.')
                ->rules(['required', 'numeric','max:100']),
                Tables\Columns\TextInputColumn::make('maxt')->label('Win Perc.')
                ->rules(['required', 'numeric','max:32000']),
                Tables\Columns\TextInputColumn::make('efrom')->label('Email from')
                    ->rules(['required', 'email']),
                Tables\Columns\TextInputColumn::make('smtp')->label('Auto send via SMTP')
                    ->rules(['required']),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInfos::route('/'),
        ];
    }
}
