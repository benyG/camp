<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationGroup(): ?string
    {
        return __('main.m3');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('form.na'))
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
                Tables\Columns\TextColumn::make('name')->sortable()->label(__('form.na'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('courseRel.name')->label('Certification')->sortable(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions')
                    ->numeric()->sortable(),
                Tables\Columns\TextColumn::make('added_at')->label(__('form.aat'))
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                        $reco = $record->replicate();
                        $record->update($data);
                        $txt = '';
                        if ($record->wasChanged()) {
                            if ($record->wasChanged('name')) {
                                $txt .= "Module was changed from '$reco->name' <br>to '$record->name' <br>";
                            }
                            if (strlen($txt) > 0) {
                                \App\Models\Journ::add(auth()->user(), 'Modules', 3, $txt);
                            }
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function ($record) {
                    $txt = "Removed module ID $record->id.
                    Name: $record->name <br>
                    ";
                    \App\Models\Journ::add(auth()->user(), 'Modules', 4, $txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = "Removed module ID $value->id
                         Name: $value->name <br>
                         ";
                            \App\Models\Journ::add(auth()->user(), 'Modules', 4, $txt);
                        }
                    }),
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
