<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Rawilk\FilamentPasswordInput\Password;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    Forms\Components\Select::make('ex')->label('Type')
                    ->options(['1'=>'Admin','2'=>'User'])
                    ->rules([Rule::in(['1', '2'])]),
                Password::make('password')
                    ->password()->copyable()->regeneratePassword()
                    ->generatePasswordUsing(function ($state) {
                        return Str::password(15);
                    })->regeneratePasswordIconColor('warning')
                    ->confirmed(fn (string $operation): bool => $operation === 'create')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state)),
                Password::make('password_confirmation')
                ->hidden(fn (string $operation): bool => $operation === 'edit')
                ->password()
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('vague')->label('Session')
                ->relationship(name: 'vagueRel', titleAttribute: 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(User::query()->where('ex','<>',0)->where('id','<>',auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')->label('Verified ?')
                ->color('success')->placeholder('No')->icon('heroicon-o-check-circle')
                    ->tooltip(fn (User $record): string => "{$record->email_verified_at}")
                    ->sortable(),
                    Tables\Columns\TextColumn::make('vagueRel.name')->label('Session')->sortable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')
                ->formatStateUsing(fn (int $state): string => $state<=1?'Admin':'User')
                ->sortable(),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
