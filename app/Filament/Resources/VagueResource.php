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
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $modelLabel = 'Class';
    public static function getModelLabel(): string
    {
        return trans_choice('main.m14',1);
    }
    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m14',2);
    }

    public static function form(Form $form): Form
    {
        $ix=cache()->rememberForever('settings', function () { return \App\Models\Info::findOrFail(1);});
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('form.na'))
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('users')->label(trans_choice('main.m5',5))->multiple()
                    ->relationship(name: 'users', titleAttribute: 'name')
                    ->preload()->maxItems(fn():int=>$ix->maxcl)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
        ->modifyQueryUsing(fn (Builder $query) => $query->withCount('users'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('form.na'))
                    ->searchable(),
                    Tables\Columns\TextColumn::make('users_count')->label(__('form.stu'))
                    ->numeric()->sortable(),
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco=$record->replicate();
                    $record->update($data);
                    $txt="";
                    if($record->wasChanged()){
                        if($record->wasChanged('name')){
                            $txt.="Class name was changed from '$reco->name' <br>to '$record->name' <br>";
                        }
                       if(strlen($txt)>0) \App\Models\Journ::add(auth()->user(),'Classes',3,$txt);
                    }
                    return $record;
                }),
                Tables\Actions\DeleteAction::make()
                ->after(function ($record) {
                    $txt="Removed class ID $record->id.
                    Name: $record->name <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Classes',4,$txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                          $txt="Removed class ID $value->id
                         Name: $value->name";
                         \App\Models\Journ::add(auth()->user(),'Classes',4,$txt);
                        }
                     }),
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
