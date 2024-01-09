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
    protected static ?string $navigationGroup = 'Other';
    protected static ?string $modelLabel = 'setting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('wperc')->label('Win Perc.')
                    ->rules(['required', 'numeric','max:100']),
                Forms\Components\TextInput::make('efrom')->label('Admin Email')
                    ->required()->email(),
                Forms\Components\Toggle::make('smtp')->label('Auto send PM via SMTP')
                    ->required()->inline(false),
                Forms\Components\TextInput::make('maxt')->label('Max Exam Timer')
                    ->required()->numeric(),
                    Forms\Components\TextInput::make('mint')->label('Min Exam Timer')
                    ->required(),
                    Forms\Components\TextInput::make('minq')->label('Min. Exam Quest')
                    ->required(),
                Forms\Components\TextInput::make('maxs')->label('Q. Limit-Starter')
                    ->required()
                    ->numeric()
                    ->default(50),
                Forms\Components\TextInput::make('maxu')->label('Q. Limit-Starter')
                    ->required()
                    ->numeric()
                    ->default(60),
                Forms\Components\TextInput::make('maxp')->label('Q. Limit-Starter')
                    ->required()
                    ->numeric()
                    ->default(70),
                Forms\Components\TextInput::make('maxv')->label('Q. Limit-Starter')
                    ->required()
                    ->numeric()
                    ->default(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->where('id',1))
            ->columns([
                Tables\Columns\TextColumn::make('wperc')->label('Win Perc.'),
                Tables\Columns\TextColumn::make('maxt')->label('Max Exam Timer'),
                Tables\Columns\IconColumn::make('smtp')->label('Auto send via SMTP')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInfos::route('/'),
            'edit' => Pages\EditInfo::route('/{record}/edit'),
        ];
    }
}
