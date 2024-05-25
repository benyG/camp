<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfoResource\Pages;
use App\Models\Info;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Rawilk\FilamentPasswordInput\Password;

class InfoResource extends Resource
{
    protected static ?string $model = Info::class;

    protected static ?int $navigationSort = 500;

    protected static ?string $navigationGroup = 'Administration';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getModelLabel(): string
    {
        return trans_choice('main.m9', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('main.m9', 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('form.gen'))
                            ->schema([
                                Forms\Components\Section::make(__('form.gs'))->columns(3)
                                    ->description(__('main.in1'))
                                    ->schema([
                                        Forms\Components\TextInput::make('efrom')->label(__('form.adme'))
                                            ->required()->email()->default(env('MAIL_FROM_ADDRESS')),
                                        Forms\Components\Toggle::make('smtp')->label(__('form.ase'))
                                            ->required()->inline(false)->default(true),
                                        Forms\Components\TextInput::make('maxcl')->label(__('form.msc'))
                                            ->required()->default(20)->numeric(),
                                        Forms\Components\TextInput::make('log')->label(__('form.lgd'))
                                            ->required()->default(1)->numeric(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make(trans_choice('main.m8', 6))
                            ->schema([
                                Forms\Components\Section::make(__('form.cos'))->columns(3)
                                //->description('Some string fields contains parameters')
                                    ->schema([
                                        Forms\Components\TextInput::make('wperc')->label(__('form.wpe'))->default(80)
                                            ->rules(['required', 'numeric', 'max:100']),
                                        Forms\Components\TextInput::make('taff')->label(__('form.tasd'))->default(30)
                                            ->rules(['required', 'numeric', 'max:255'])->numeric()->step(5),
                                        Forms\Components\TextInput::make('minq')->label(__('form.omq'))
                                            ->required()->default(5)->numeric(),
                                    ]),
                                Forms\Components\Section::make(__('form.tim').'s')->columns(5)
                                    ->description(__('main.in2'))
                                    ->schema([
                                        Forms\Components\TextInput::make('mint')->label(__('form.emt'))
                                            ->required()->default(15),
                                        Forms\Components\TextInput::make('maxts')->label(__('form.lim').' - Starter')
                                            ->required()->numeric()->default(20),
                                        Forms\Components\TextInput::make('maxtu')->label(__('form.lim').' - User')
                                            ->required()->numeric()->default(60),
                                        Forms\Components\TextInput::make('maxtp')->label(__('form.lim').' - Pro')
                                            ->required()->numeric()->default(120),
                                        Forms\Components\TextInput::make('maxtv')->label(__('form.lim').' - VIP')
                                            ->required()->numeric()->default(240),
                                    ]),
                                Forms\Components\Section::make(__('form.tqu'))->columns(5)
                                    ->description(__('main.in3').' '.Str::upper(__('form.tst')).'s')
                                    ->schema([
                                        Forms\Components\TextInput::make('maxs')->label(__('form.lim').' Q.'.'-Starter')
                                            ->required()->numeric()->default(10),
                                        Forms\Components\TextInput::make('maxu')->label(__('form.lim').' Q.'.'-User')
                                            ->required()->numeric()->default(30),
                                        Forms\Components\TextInput::make('maxp')->label(__('form.lim').' Q.'.'-Pro')
                                            ->required()->numeric()->default(50),
                                        Forms\Components\TextInput::make('maxv')->label(__('form.lim').' Q.'.'-VIP')
                                            ->required()->numeric()->default(100),
                                    ]),
                                Forms\Components\Section::make(__('form.equ'))->columns(5)
                                    ->description(__('main.in3').' '.Str::upper(__('form.exa')).'s')
                                    ->schema([
                                        Forms\Components\TextInput::make('maxes')->label(__('form.lim').' Q.-Starter')
                                            ->required()->numeric()->default(10),
                                        Forms\Components\TextInput::make('maxeu')->label(__('form.lim').' Q.-User')
                                            ->required()->numeric()->default(50),
                                        Forms\Components\TextInput::make('maxep')->label(__('form.lim').' Q.-Pro')
                                            ->required()->numeric()->default(100),
                                        Forms\Components\TextInput::make('maxev')->label(__('form.lim').' Q.-VIP')
                                            ->required()->numeric()->default(200),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('form.ai'))
                            ->schema([
                                Forms\Components\Section::make(__('form.apis'))->columns(2)
                               // ->description('Some string fields contains parameters')
                                    ->schema([
                                        Password::make('apk')->label(__('form.apik'))
                                            ->required()
                                            ->dehydrateStateUsing(fn (string $state): string => $state != Info::first()->apk ? Crypt::encryptString($state) : $state)
                                            ->dehydrated(fn (?string $state): bool => filled($state)),
                                        Forms\Components\TextInput::make('endp')->label(__('form.enu'))
                                            ->required()->rules(['url']),
                                        Forms\Components\TextInput::make('model')->label(__('form.aimo'))
                                            ->required()->rules(['max:255']),
                                    ]),
                                Forms\Components\Section::make(__('form.cont'))
                                    ->description(__('main.in4'))
                                    ->schema([
                                        Forms\Components\Textarea::make('cont1')->label(__('form.cont1'))
                                            ->required(),
                                        Forms\Components\Textarea::make('cont2')->label(__('form.cont2'))
                                            ->required(),
                                        Forms\Components\Textarea::make('cont3')->label(__('form.cont3'))
                                            ->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('id', 1))
            ->columns([
                Tables\Columns\TextColumn::make('wperc')->label(__('form.wpe')),
                Tables\Columns\TextColumn::make('mint')->label(__('form.emt')),
                Tables\Columns\TextColumn::make('efrom')->label(__('form.adme')),
                Tables\Columns\IconColumn::make('smtp')->label(__('form.ase'))->boolean(),
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
