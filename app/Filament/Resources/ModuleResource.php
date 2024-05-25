<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Course;
use App\Models\Module;
use App\Models\Prov;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?int $navigationSort = 50;

    protected static ?string $slug = 'domains';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationGroup(): ?string
    {
        return __('main.m3');
    }

    public static function getModelLabel(): string
    {
        return trans_choice('main.m17', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m17', 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('form.na'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('prov')->label(__('main.m16'))->required()
                    ->options(Prov::all()->pluck('name', 'id'))->default(session('1providers'))
                    ->preload()->live(),
                Forms\Components\Select::make('course')->label('Certification')->required()
                    ->relationship(name: 'courseRel', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Get $get, string $operation) {
                        if ($operation == 'create') {
                            return $query->where('prov', $get('prov'));
                        } else {
                            if (is_numeric($get('prov'))) {
                                return $query->where('prov', $get('prov'));
                            } else {
                                return $query;
                            }
                        }
                    }),
                Forms\Components\Textarea::make('descr')->columnSpanFull()->label('Description'),
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
                Tables\Filters\SelectFilter::make('course')->label(__('form.cl'))
                    ->options(Course::with('provRel')->get()->reject(function (Course $value, int $key) {
                        return is_null($value->provRel);
                    })->mapToGroups(function (Course $item, int $key) {
                        return [$item->provRel->name => [$item->id => $item->name]];
                    })->map(function ($item, string $key) {
                        return $item->reduce(function ($ca, $it) {
                            if (is_null($ca)) {
                                $ca = [];
                            }

                            return array_merge($ca, $it);
                        });
                    })->toArray())->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        $data['prov'] = $record->provRel->id;

                        return $data;
                    })
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
