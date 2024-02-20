<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfoResource\Pages;
use App\Filament\Resources\InfoResource\RelationManagers;
use App\Models\Info;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;
use Illuminate\Support\Facades\Crypt;

class InfoResource extends Resource
{
    protected static ?string $model = Info::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'setting';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Settings')->columns(3)
                ->description('Here you can find some common settings for your whole application')
                ->schema([
                    Forms\Components\TextInput::make('efrom')->label('Admin Email')
                        ->required()->email()->default(env('MAIL_FROM_ADDRESS')),
                    Forms\Components\Toggle::make('smtp')->label('Auto send PM via SMTP')
                        ->required()->inline(false)->default(true)->columnSpan(2),
                    Forms\Components\TextInput::make('wperc')->label('Win Perc.')->default(80)
                        ->rules(['required', 'numeric','max:100']),
                        Forms\Components\TextInput::make('minq')->label('Overall minimum Questions')
                        ->required()->default(5)->numeric(),
                        Forms\Components\TextInput::make('maxcl')->label('Max students per class')
                        ->required()->default(20)->numeric(),
                        ]),
                Forms\Components\Section::make('Timers')->columns(5)
                ->description('For each type of users, set the maximum timer for exams (in minutes)')
                ->schema([
                    Forms\Components\TextInput::make('mint')->label('Exam minimum Timer')
                    ->required()->default(15),
                    Forms\Components\TextInput::make('maxts')->label('Limit - Starter')
                    ->required()->numeric()->default(20),
                Forms\Components\TextInput::make('maxtu')->label('Limit - User')
                    ->required()->numeric()->default(60),
                Forms\Components\TextInput::make('maxtp')->label('Limit - Pro')
                    ->required()->numeric()->default(120),
                Forms\Components\TextInput::make('maxtv')->label('Limit - VIP')
                    ->required()->numeric()->default(240),
                ]),
                Forms\Components\Section::make('Tests questions')->columns(5)
                ->description('For each type of users, set the number of questions that can be generated for TESTS')
                ->schema([
                Forms\Components\TextInput::make('maxs')->label('Q. Limit-Starter')
                    ->required()->numeric()->default(10),
                Forms\Components\TextInput::make('maxu')->label('Q. Limit-User')
                    ->required()->numeric()->default(30),
                Forms\Components\TextInput::make('maxp')->label('Q. Limit-Pro')
                    ->required()->numeric()->default(50),
                Forms\Components\TextInput::make('maxv')->label('Q. Limit-VIP')
                    ->required()->numeric()->default(100),
               ]),
               Forms\Components\Section::make('Exams questions')->columns(5)
               ->description('For each type of users, set the number of questions that can be generated for EXAMS')
               ->schema([
               Forms\Components\TextInput::make('maxes')->label('Q. Limit-Starter')
                   ->required()->numeric()->default(10),
               Forms\Components\TextInput::make('maxeu')->label('Q. Limit-User')
                   ->required()->numeric()->default(50),
               Forms\Components\TextInput::make('maxep')->label('Q. Limit-Pro')
                   ->required()->numeric()->default(100),
               Forms\Components\TextInput::make('maxev')->label('Q. Limit-VIP')
                   ->required()->numeric()->default(200),
              ]),
              Forms\Components\Section::make('AI Settings')->columns(2)
              ->description('Some string fields contains parameters')
              ->schema([
              Password::make('apk')->label('API Key')
                  ->required()
                  ->dehydrateStateUsing(fn (string $state): string => Crypt::encryptString($state))
                  ->dehydrated(fn (?string $state): bool => filled($state)),
                  Forms\Components\TextInput::make('endp')->label('Endpoint URL')
                  ->required()->rules(['url']),
                  Forms\Components\TextInput::make('model')->label('AI Model')
                  ->required()->rules(['max:255']),
                  Forms\Components\Textarea::make('cont1')->label('Tips context')
                  ->required(),
                  Forms\Components\Textarea::make('cont2')->label('A&E context')
                  ->required(),
              Forms\Components\Textarea::make('cont3')->label('Perf Analysis context')
                  ->required(),
             ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->where('id',1))
            ->columns([
                Tables\Columns\TextColumn::make('wperc')->label('Win Perc.'),
                Tables\Columns\TextColumn::make('mint')->label('Min Exam Timer'),
                Tables\Columns\TextColumn::make('efrom')->label('Support Email'),
                Tables\Columns\IconColumn::make('smtp')->label('Auto send via SMTP')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInfos::route('/'),
            'edit' => Pages\EditInfo::route('/{record}/edit'),
        ];
    }
}
