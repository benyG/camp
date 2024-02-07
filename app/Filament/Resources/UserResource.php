<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Rawilk\FilamentPasswordInput\Password;
use App\Models\User;
use App\Models\Course;
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
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Other';

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
                    ->options(['1' => 'Admin','2' => 'Starter','3' => 'User','4' => 'Pro','5' => 'VIP'])
                    ->rules([Rule::in(['1','2','3','4','5'])]),
                Password::make('password')
                ->regex('/^\S*(?=.*\d)(?=\S*[\W])[a-zA-Z\d]\S*$/i')
                        ->validationMessages([
                            'regex' => "There should be at least one uppercase and lowercase letter, one special character, and one digit. No spaces",
                        ])
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
                Forms\Components\Select::make('vague')->label('Class')
                ->relationship(name: 'vagueRel', titleAttribute: 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->striped()
        ->query(User::with('vagueRel')->where('ex','<>',0)->where('id','<>',auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')->label('Verified ?')
                ->toggleable(isToggledHiddenByDefault: true)
                ->color('success')->placeholder('No')->icon('heroicon-o-check-circle')
                    ->tooltip(fn (User $record): string => "{$record->email_verified_at}")
                    ->sortable(),
                    Tables\Columns\TextColumn::make('vagueRel.name')->label('Class')->sortable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')->badge()
                ->formatStateUsing(fn (int $state): string => match ($state) {0 => "indigo",
                    1 => "Admin", 2 => "Starter", 3 => "User", 4 => "Pro", 5 => "VIP"})
                    ->color(fn (int $state): string => match ($state) {0 => "S. Admin",
                        1 => "gray", 2 => "info", 3 => "success", 4 => "danger", 5 => "warning"})
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
                Tables\Filters\SelectFilter::make('vague')
                ->relationship(name: 'vagueRel', titleAttribute: 'name')->multiple()
                ->searchable()
                ->preload()
            ])
            ->actions([
                Tables\Actions\Action::make('jjj')->color('success')->label('Portfolio')
                ->fillForm(fn (User $record): array => [
                    'cou' => \App\Models\Course::join('users_course', 'users_course.course', '=', 'courses.id')
                    ->where('user',$record->id)->where('approve',true)->pluck('courses.id'),
                ])
                ->form([
                    Forms\Components\Select::make('cou')
                    ->options(\App\Models\Course::all()->pluck('name', 'id'))
                        ->label('Certifications')
                        ->multiple()->preload(),
                ])
                ->action(function ($data,$record):void {
                      $record->courses()->sync($data['cou']);
                    foreach ($data['cou'] as $va) {
                        $record->courses()->updateExistingPivot($va, ['approve' => true]);
                    }
                    Notification::make('es')->success()->title('Certifications saved')->send();
                      })->button()->visible(fn (): bool =>auth()->user()->ex==0)
                    ->modalHeading('Manage a user portfolio')
                    ->modalSubmitActionLabel('Grant access')
                    ->modalDescription(fn(User $record):string=>$record->name),
                Tables\Actions\Action::make('resend')->color(fn(User $record):string=>$record->ax?'danger':'info')->label(fn(User $record):string=>$record->ax?'Block':'Grant')
                ->action(function (User $record) {
                    $record->ax=$record->ax? false:true; $record->save();
                  Notification::make('es')->success()->title('User '.$record->name.' is now '.($record->ax?'able to access the platform':'blocked'))->send();
                    })->button()->visible(fn (): bool =>auth()->user()->ex==0)->iconButton()->icon(fn(User $record):string=>$record->ax?'heroicon-o-no-symbol':'heroicon-o-user-circle')
                    ->requiresConfirmation()->modalIcon(fn(User $record):string=>$record->ax?'heroicon-o-no-symbol':'heroicon-o-user-circle')
                    ->modalHeading(fn(User $record):string=>$record->ax?'Block access':'Grant access')
                    ->modalDescription(fn(User $record):string=>$record->ax?'Are you sure you\'d like to block user \''.$record->name.'\'?':
                        'Are you sure you\'d like to grant user \''.$record->name.'\' access to the platform ?'),
                        Tables\Actions\EditAction::make()->iconButton()->after(function (User $record,$data) { if($record->ex > auth()->user()->ex) {$record->ex=intval($data['ex']);$record->save();}}),
                        Tables\Actions\DeleteAction::make()->iconButton(),
      ])
      ->bulkActions([
          Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
