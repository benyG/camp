<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvResource\Pages;
use App\Models\Prov;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProvResource extends Resource
{
    protected static ?string $model = Prov::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 65;

    protected static ?string $modelLabel = 'provider';

    protected static ?string $slug = 'providers';

    public static function getNavigationGroup(): ?string
    {
        return __('main.m3');
    }

    public static function getModelLabel(): string
    {
        return trans_choice('main.m15', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m15', 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('form.na'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('form.na'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('courses_count')->counts('courses')->label('Certifications')
                    ->numeric()->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco = $record->replicate();
                    $record->update($data);
                    $txt = '';
                    if ($record->wasChanged()) {
                        if ($record->wasChanged('name')) {
                            $txt .= "Name was changed from '$reco->name' to '$record->name' <br>";
                        }
                        if (strlen($txt) > 0) {
                            \App\Models\Journ::add(auth()->user(), 'Providers', 3, 'Provider ID '.$record->id.'<br>'.$txt);
                        }
                    }

                    return $record;
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    $txt = "Removed provider $record->name";
                    \App\Models\Journ::add(auth()->user(), 'Providers', 4, $txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = "Removed certification $value->name";
                            \App\Models\Journ::add(auth()->user(), 'Certifications', 4, $txt);
                        }
                    }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProvs::route('/'),
        ];
    }
}
