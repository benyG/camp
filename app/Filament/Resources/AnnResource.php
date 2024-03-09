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
                Tables\Actions\EditAction::make()
                ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                    $reco=$record->replicate();
                    $record->update($data);
                    $txt="";
                    if($record->wasChanged()){
                        if($record->descr!=$reco->descr){
                            $txt.="Description was changed from '$reco->descr' to '$record->descr' <br>";
                        }
                        if($record->url!=$reco->url){
                            $txt.="Url was changed from '$reco->url' to '$record->url' <br>";
                        }
                        if($record->hid!=$reco->hid){
                            $txt.="Display was changed from '".(intval($reco->hid)==0?'No':'Yes')."' to '".($record->hid==0?'No':'Yes')."' <br>";
                        }
                        if($record->due!=$reco->due){
                            $txt.="Due date was changed from '$reco->due' to '$record->due' <br>";
                        }
                        if($record->type!=$reco->type){
                        $txt.="Type of users was changed from '".str_replace(array('1','2','3','4','5'),array('Admin','Starter','User','Pro','VIP'),implode(',',$reco->type))."' to '".
                            str_replace(array('1','2','3','4','5'),array('Admin','Starter','User','Pro','VIP'),implode(',',$record->type))."' <br>";
                        }
                        if($record->wasChanged('text')){
                            $txt.="The content was changed. <br>";
                        }
                       if(strlen($txt)>0) \App\Models\Journ::add(auth()->user(),'Announcements',3,"Announcement ID ".$record->id."<br>".$txt);
                    }
                    return $record;
                }),
                Tables\Actions\DeleteAction::make()->after(function ($record) {
                    $txt="Removed announcement ID $record->id.
                    Description: $record->descr <br>
                    Url: $record->url <br>
                    Users: ".str_replace(array('1','2','3','4','5'),array('Admin','Starter','User','Pro','VIP'),implode(',',$record->type))." <br>
                    Display: ".(intval($record->hid)==0?'No':'Yes')." <br>
                    Due date: $record->due <br>
                    ";
                    \App\Models\Journ::add(auth()->user(),'Announcement',4,$txt);
            }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt="Removed announcement ID $value->id.
                            Description: $value->descr <br>
                            Url: $value->url <br>
                            Users: ".str_replace(array('1','2','3','4','5'),array('Admin','Starter','User','Pro','VIP'),implode(',',$value->type))." <br>
                            Display: ".(intval($value->hid)==0?'No':'Yes')." <br>
                            Due date: $value->due <br>
                                 ";
                         \App\Models\Journ::add(auth()->user(),'Answers',4,$txt);
                        }
                     }),
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
