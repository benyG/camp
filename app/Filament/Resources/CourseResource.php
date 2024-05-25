<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?int $navigationSort = 60;

    protected static ?string $modelLabel = 'certification';

    protected static ?string $slug = 'certifications';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $hasTitleCaseModelLabel = false;

    public static function getNavigationGroup(): ?string
    {
        return __('main.m3');
    }

    public static function getModelLabel(): string
    {
        return trans_choice('main.m7', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m7', 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('prov')->label(__('main.m15'))->required()
                ->relationship(name: 'provRel', titleAttribute: 'name'),
                Forms\Components\TextInput::make('name')->label(__('form.na'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descr')->columnSpanFull()->label('Description'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('form.na'))
                    ->searchable()->sortable()->description(fn (Course $record): ?string => $record->descr.(" (".($record->provRel->name??'N/A').")")),
                Tables\Columns\TextColumn::make('modules_count')->counts('modules')->label('Modules')
                    ->numeric()->sortable(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions')
                    ->numeric()->sortable(),
                Tables\Columns\TextColumn::make('users_count')->counts('users')->label(trans_choice('main.m5', 5))
                    ->numeric()->sortable(),
                Tables\Columns\IconColumn::make('pub')->boolean()->label(Str::ucfirst(__('form.pub')))->sortable(),
                Tables\Columns\TextColumn::make('added_at')->label(__('form.aat'))
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('prov')
                ->relationship(name: 'provRel', titleAttribute: 'name')
                ->searchable()->label(__('main.m16'))->multiple()
                ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('resend')->iconButton()->icon(fn (Course $record): string => $record->pub ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Course $record): string => $record->pub ? 'danger' : 'info')
                    ->label(fn (Course $record): string => $record->pub ? Str::ucfirst(__('form.pub2')) : Str::ucfirst(__('form.pub1')))
                    ->after(function ($record) {
                        $txt = "Certification '$record->name' ".($record->pub ? '' : 'un').'published..';
                        \App\Models\Journ::add(auth()->user(), 'Certifications', 3, $txt);
                    })
                    ->action(function (Course $record) {
                        $record->pub = $record->pub ? false : true;
                        $record->save();
                        Notification::make('es')->success()->title(__('main.ce1', ['name' => $record->name, 'opt' => ($record->pub ? __('form.pub') : __('form.pub2'))]))->send();
                    })->visible(fn (): bool => auth()->user()->ex == 0)
                    ->requiresConfirmation()->modalIcon(fn (Course $record): string => $record->pub ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->modalHeading(fn (Course $record): string => $record->pub ? Str::ucfirst(__('form.pub2')) : Str::ucfirst(__('form.pub1')))
                    ->modalDescription(fn (Course $record): string => $record->pub ? __('main.ce2') : __('main.ce3')),
                Tables\Actions\Action::make('resnd')->color('warning')->label('Config')
                    ->url(fn (Course $record): string => CourseResource::getUrl('config', ['record' => $record]))
                    ->iconButton()->icon('heroicon-o-cog-6-tooth')->visible(fn (): bool => auth()->user()->ex == 0),
                Tables\Actions\EditAction::make()->iconButton()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                        $reco = $record->replicate();
                        $record->update($data);
                        $txt = '';
                        if ($record->wasChanged()) {
                            if ($record->wasChanged('name')) {
                                $txt .= "Name was changed from '$reco->name' to '$record->name' <br>";
                            }
                            if ($record->wasChanged('descr')) {
                                $txt .= "Description was changed from '$reco->descr' <br>to '$record->descr' <br>";
                            }
                            if (strlen($txt) > 0) {
                                \App\Models\Journ::add(auth()->user(), 'Certifications', 3, 'Course ID '.$record->id.'<br>'.$txt);
                            }
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function ($record) {
                    $txt = "Removed certification $record->name";
                    \App\Models\Journ::add(auth()->user(), 'Certifications', 4, $txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('def')->modalHeading(Str::ucfirst(__('form.pub1')))->label(Str::ucfirst(__('form.pub1')))
                        ->requiresConfirmation()->color('success')->modalIcon('heroicon-o-eye')
                        ->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                            foreach ($record as $value) {
                                $txt = "Certification '$value->name' published.";
                                \App\Models\Journ::add(auth()->user(), 'Certifications', 3, $txt);
                            }
                        })
                        ->action(function (Collection $record) {
                            $record->each(function (Course $rec, int $key) {
                                $rec->pub = true;
                                $rec->save();
                            });
                            Notification::make('e')->title(__('main.ce4'))
                                ->iconColor('success')->send();
                        })->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('Delete')->modalHeading(Str::ucfirst(__('form.pub2')))->label(Str::ucfirst(__('form.pub2')))
                        ->requiresConfirmation()->color('danger')->modalIcon('heroicon-o-eye-slash')->modalIconColor('warning')
                        ->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                            foreach ($record as $value) {
                                $txt = "Certification '$value->name' unpublished.";
                                \App\Models\Journ::add(auth()->user(), 'Certifications', 3, $txt);
                            }
                        })
                        ->action(function (Collection $record) {
                            $record->each(function (Course $rec, int $key) {
                                $rec->pub = false;
                                $rec->save();
                            });
                            Notification::make('e7')->title(__('main.ce5'))->icon('heroicon-o-eye-slash')
                                ->iconColor('success')->send();
                        })->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = "Removed certification $value->name";
                            \App\Models\Journ::add(auth()->user(), 'Certifications', 4, $txt);
                        }
                    }),
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
