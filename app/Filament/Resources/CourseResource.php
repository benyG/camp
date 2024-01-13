<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Teachers';
    protected static ?string $modelLabel = 'certification';
    protected static ?string $slug = 'certifications';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descr')->columnSpanFull()->label('Description'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable()->description(fn (Course $record): ?string => $record->descr),
                    Tables\Columns\TextColumn::make('modules_count')->counts('modules')->label('Modules')
                    ->numeric()->sortable(),
                    Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions')
                    ->numeric()->sortable(),
                    Tables\Columns\TextColumn::make('users_count')->counts('users')->label('Users')
                    ->numeric()->sortable(),
                    Tables\Columns\IconColumn::make('pub')->boolean()->label('Published')->sortable(),
                    Tables\Columns\TextColumn::make('added_at')
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('resend')->color(fn(Course $record):string=>$record->pub?'danger':'info')->label(fn(Course $record):string=>$record->pub?'Unpublish':'Publish')
                ->action(function (Course $record) {
                    $record->pub=$record->pub? false:true; $record->save();
                  Notification::make('es')->success()->title($record->name.' certiication sucessfully '.($record->pub?'':'un').'published')->send();
                    })->button()->visible(fn (): bool =>auth()->user()->ex==0)
                    ->requiresConfirmation()->modalIcon(fn(Course $record):string=>$record->pub?'heroicon-o-eye-slash':'heroicon-o-eye')
                    ->modalHeading(fn(Course $record):string=>$record->pub?'Unpublish':'Publish')
                    ->modalDescription(fn(Course $record):string=>$record->pub?'Are you sure you\'d like to unpublish \''.$record->name.'\' certification? This will hide it to the users.':
                        'Are you sure you\'d like to publish \''.$record->name.'\' certification? This will make it visible to the public.'),
                Tables\Actions\Action::make('resend')->color('warning')->label('Config')
                ->url(fn (Course $record): string => CourseResource::getUrl('config', ['record' => $record]))
                ->button()->visible(fn (): bool =>auth()->user()->ex==0),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('def')->modalHeading('Publish')->label('Publish selected')
                    ->requiresConfirmation()->color('success')->modalIcon('heroicon-o-eye')
                    ->action(function (Collection $record) {
                        $record->each(function (Course $rec, int $key) { $rec->pub=true;$rec->save();});
                        Notification::make('e')->title('Certifications published successfully')
                        ->iconColor('success')->send();
                    })->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('Delete')->modalHeading('Unpublish')->label('Unpublish selected')
                    ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-eye-slash')->modalIconColor('warning')
                    ->action(function (Collection $record) {
                        $record->each(function (Course $rec, int $key) { $rec->pub=false;$rec->save();});
                        Notification::make('e7')->title('Certifications hidden successfully')->icon('heroicon-o-eye-slash')
                        ->iconColor('success')->send();
                    })->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourses::route('/'),
            'approve' => Pages\CertApproval::route('/approvals'),
            'config' => Pages\CertConfig::route('/cfg-{record}'),
        ];
    }
}
