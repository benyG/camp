<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationGroup = 'Administrationi';

    protected static bool $hasTitleCaseModelLabel = false;

    public static function getModelLabel(): string
    {
        return trans_choice('main.m5', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m5', 2);
    }

    public static function form(Form $form): Form
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('form.na'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('ex')->label('Type')
                    ->options(['1' => 'Admin', '2' => 'Starter', '3' => 'User', '4' => 'Pro', '5' => 'VIP','9'=>__('main.Guest')])
                    ->rules([Rule::in(['1', '2', '3', '4', '5'])]),
                Password::make('password')->label(__('form.pwd'))
                    ->regex('/^\S*(?=.*\d)(?=\S*[\W])[a-zA-Z\d]\S*$/i')
                    ->validationMessages([
                        'regex' => __('form.e1'),
                    ])
                    ->password()->copyable()->regeneratePassword(using: fn () => Str::password(15),color:'warning')
                    ->confirmed(fn (string $operation): bool => $operation === 'create')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state)),
                Password::make('password_confirmation')->label(__('form.pwd1'))
                    ->hidden(fn (string $operation): bool => $operation === 'edit')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('vagues')->label(__('form.cl'))->multiple()
                    ->relationship(name: 'vagues', titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->withCount('users')->having('users_count', '<=', $ix->maxcl))
                    ->preload(),
                Forms\Components\Select::make('tz')->label('Timezone')->required()
                    ->options(function () {
                        $oo = mtz();
                        $ap = [];
                        foreach ($oo as $timezone) {
                            $ap[$timezone['timezone']] = '(GMT '.$timezone['offset'].') '.$timezone['name'];
                        }

                        return $ap;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
            ->query(User::with('vagues')->where('ex', '<>', 0)->where('id', '<>', auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('form.na'))
                    ->searchable()->description(fn ($record): string => $record->email),
                Tables\Columns\IconColumn::make('email_verified_at')->label(__('form.vef').__('main.space').'?')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('success')->placeholder(__('form.no'))->icon('heroicon-o-check-circle')
                    ->tooltip(fn (User $record): string => "{$record->email_verified_at}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('vagues.name')->label(__('form.cl'))->sortable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'S. Admin',
                        1 => 'Admin', 2 => 'Starter', 3 => 'User', 4 => 'Pro', 5 => 'VIP',
                        default=>__('main.Guest')
                    })
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'S. Admin',
                        1 => 'gray', 2 => 'info', 3 => 'success', 4 => 'danger', 5 => 'warning',
                        default=>'purple'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('ix')->label(__('main.aic'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label(__('form.cat'))
                    ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label(__('form.uat'))
                    ->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vagues')->label(__('form.cl'))
                    ->relationship(name: 'vagues', titleAttribute: 'name')->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('jjj')->color('success')->label('Portfolio')
                    ->fillForm(fn (User $record): array => [
                        'cou' => \App\Models\Course::join('users_course', 'users_course.course', '=', 'courses.id')
                            ->where('user', $record->id)->where('approve', true)->pluck('courses.id'),
                    ])
                    ->form([
                        Forms\Components\Select::make('cou')
                            ->options(\App\Models\Course::all()->pluck('name', 'id'))
                            ->label('Certifications')
                            ->multiple()->preload(),
                    ])
                    ->action(function ($data, $record): void {
                        $record->courses()->sync($data['cou']);
                        foreach ($data['cou'] as $va) {
                            $record->courses()->updateExistingPivot($va, ['approve' => true]);
                        }
                        Notification::make('es')->success()->title('Certifications '.__('form.sav'))->send();
                        $txt = "User $record->name ($record->email) portfolio saved.";
                        \App\Models\Journ::add(auth()->user(), 'Users', 3, $txt);
                    })->button()->visible(fn (): bool => auth()->user()->ex == 0)
                    ->modalHeading(__('main.us1'))
                    ->modalSubmitActionLabel(__('form.gra'))
                    ->modalDescription(fn (User $record): string => $record->name),
                Tables\Actions\Action::make('resend')->color(fn (User $record): string => $record->ax ? 'danger' : 'info')->label(fn (User $record): string => $record->ax ? 'Block' : 'Grant')
                    ->after(function ($record) {
                        $txt = 'User '.$record->name.' ('.$record->email.') is now '.($record->ax ? 'able to access the platform' : 'blocked');
                        \App\Models\Journ::add(auth()->user(), 'Users', 3, $txt);
                    })
                    ->action(function (User $record) {
                        $record->ax = $record->ax ? false : true;
                        $record->save();
                        Notification::make('es')->success()->title(__('main.us2', ['name' => $record->name]).' '.($record->ax ? __('main.us3') : __('form.blo')))->send();
                    })->button()->visible(fn (): bool => auth()->user()->ex == 0)->iconButton()->icon(fn (User $record): string => $record->ax ? 'heroicon-o-no-symbol' : 'heroicon-o-user-circle')
                    ->requiresConfirmation()->modalIcon(fn (User $record): string => $record->ax ? 'heroicon-o-no-symbol' : 'heroicon-o-user-circle')
                    ->modalHeading(fn (User $record): string => $record->ax ? __('form.bloa') : __('form.gra'))
                    ->modalDescription(fn (User $record): string => $record->ax ? __('main.us4').' \''.$record->name.'\'?' :
                    __('main.us5', ['name' => $record->name]).__('main.space').'?'),
                Tables\Actions\EditAction::make()->iconButton()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model {
                        $reco = $record->replicate();
                        $record->update($data);
                        $txt = '';
                        if ($record->wasChanged('name')) {
                            $txt .= "Name was changed from '$reco->name' <br>to '$record->name' <br>";
                        }
                        if ($record->wasChanged('email')) {
                            $txt .= "Email was changed from '$reco->email' <br>to '$record->email' <br>";
                        }
                        if ($record->ex != intval($data['ex'])) {
                            $txt .= "Rank was changed from '".match (intval($data['ex'])) {
                                0 => 'S. Admin',
                                1 => 'Admin', 2 => 'Starter', 3 => 'User', 4 => 'Pro', 5 => 'VIP'
                            }
                            ."' <br>to '".match ($record->ex) {
                                0 => 'S. Admin',
                                1 => 'Admin', 2 => 'Starter', 3 => 'User', 4 => 'Pro', 5 => 'VIP'
                            }."' <br>";
                        }
                        if ($record->wasChanged('tz')) {
                            $txt .= "Timezone was changed from '$reco->tz' <br>to '$record->tz' <br>";
                        }
                        if ($record->password != $reco->password) {
                            $txt .= 'Password was changed <br>';
                        }
                        if ($record->vagues != $reco->vagues) {
                            $txt .= 'Class was changed <br>';
                        }
                        if (strlen($txt) > 0) {
                            \App\Models\Journ::add(auth()->user(), 'Users', 3, "User $record->name ($record->email) was changed<br>".$txt);
                        }

                        return $record;
                    })->after(function (User $record, $data) {
                        if ($record->ex > auth()->user()->ex) {
                            $record->ex = intval($data['ex']);
                            $record->save();
                        }
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function ($record) {
                    $txt = "Removed User ID $record->id.
                            Name: $record->name ($record->email) <br>
                            ";
                    \App\Models\Journ::add(auth()->user(), 'Users', 4, $txt);
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(function (\Illuminate\Database\Eloquent\Collection $record) {
                        foreach ($record as $value) {
                            $txt = "Removed user ID $value->id
                         Name: $value->name ($value->email)";
                            \App\Models\Journ::add(auth()->user(), 'Users', 4, $txt);
                        }
                    }),
                ]),
            ])
            ->deferLoading()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
