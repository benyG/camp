<?php

namespace App\Filament\Pages;

use App\Models\Info;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Rawilk\FilamentPasswordInput\Password;

class Settings extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?int $navigationSort = 500;

    protected static ?string $navigationGroup = 'Administration';

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    public Info $info;

    public function mount(): void
    {
        $this->info = Info::findOrFail(1);
        $this->form->fill($this->info->toArray());
    }

    public static function canAccess(): bool
    {
        return auth()->user()->ex == 0;
    }

    public static function getNavigationLabel(): string
    {
        return trans_choice('main.m9', 2);
    }

    public function getTitle(): string|Htmlable
    {
        return trans_choice('main.m9', 2);
    }

    public function getHeading(): string
    {
        return trans_choice('main.m9', 2);
    }

    public function form(Form $form): Form
    {
        $cgrid = [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
        ];
        $sgrid = [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
        ];

        //  str_pad(,2,"0",1);
        return $form->model($this->info)->statePath('data')
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
                                        Forms\Components\TextInput::make('mia')->label(__('form.mia'))
                                            ->required()->default(0)->numeric(),
                                        Forms\Components\TextInput::make('mlc')->label(__('form.mia'))
                                            ->required()->default(30)->numeric(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Packages')
                            ->schema([
                                Forms\Components\Section::make(__('form.cos'))->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('wperc')->label(__('form.wpe'))->default(80)
                                            ->rules(['required', 'numeric', 'max:100']),
                                        Forms\Components\TextInput::make('taff')->label(__('form.tasd'))->default(30)
                                            ->rules(['required', 'numeric', 'max:255'])->numeric()->step(5),
                                        Forms\Components\TextInput::make('minq')->label(__('form.omq'))
                                            ->required()->default(5)->numeric(),
                                        Forms\Components\TextInput::make('mint')->label(__('form.emt'))
                                            ->required()->default(15),
                                    ]),
                                Forms\Components\Section::make('Guest')->columns($cgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('maxtg')->label(__('form.tim').'s Exam.')
                                            ->required()->numeric()->default(20),
                                        Forms\Components\TextInput::make('maxeg')->label(__('form.equ'))
                                            ->required()->numeric()->default(10),
                                        Forms\Components\TextInput::make('iac_g')->label(__('main.aic'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('saa_g')->label(__('form.saa'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_g')->label(__('form.eac'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\Toggle::make('tec_g')->label(__('form.tec'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('tga_g')->label(__('form.tga'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ftg_g')->label(__('form.ftg'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('sta_g')->label(__('form.sta'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('pa_g')->label(__('form.pa'))
                                            ->required()->default(false)->inline(false),

                                    ]),

                                Forms\Components\Section::make('Free')->columns($cgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('maxts')->label(__('form.tim').'s Exam.')
                                            ->required()->numeric()->default(20),
                                        Forms\Components\TextInput::make('maxs')->label(__('form.tqu'))
                                            ->required()->numeric()->default(10),
                                        Forms\Components\TextInput::make('maxes')->label(__('form.equ'))
                                            ->required()->numeric()->default(10),
                                        Forms\Components\TextInput::make('iac_f')->label(__('main.aic'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('saa_f')->label(__('form.saa'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_f')->label(__('form.eac'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\Toggle::make('tec_f')->label(__('form.tec'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('tga_f')->label(__('form.tga'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ftg_f')->label(__('form.ftg'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('sta_f')->label(__('form.sta'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('pa_f')->label(__('form.pa'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ecl_f')->label(__('form.ecl'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ss_f')->label(__('form.ss'))
                                            ->required()->default(false)->inline(false),
                                  ]),
                                Forms\Components\Section::make('Basic')->columns($cgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('maxtu')->label(__('form.tim').'s Exam.')
                                            ->required()->numeric()->default(60),
                                        Forms\Components\TextInput::make('maxu')->label(__('form.tqu'))
                                            ->required()->numeric()->default(30),
                                        Forms\Components\TextInput::make('maxeu')->label(__('form.equ'))
                                            ->required()->numeric()->default(50),
                                        Forms\Components\TextInput::make('iac_b')->label(__('main.aic'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('saa_b')->label(__('form.saa'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_b')->label(__('form.eac'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\Toggle::make('tec_b')->label(__('form.tec'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('tga_b')->label(__('form.tga'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ftg_b')->label(__('form.ftg'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('sta_b')->label(__('form.sta'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('pa_b')->label(__('form.pa'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ecl_b')->label(__('form.ecl'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ss_b')->label(__('form.ss'))
                                            ->required()->default(false)->inline(false),
                                    ]),
                                Forms\Components\Section::make('Standard')->columns($cgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('maxtp')->label(__('form.tim').'s Exam.')
                                            ->required()->numeric()->default(120),
                                        Forms\Components\TextInput::make('maxp')->label(__('form.tqu'))
                                            ->required()->numeric()->default(50),
                                        Forms\Components\TextInput::make('maxep')->label(__('form.equ'))
                                            ->required()->numeric()->default(100),
                                        Forms\Components\TextInput::make('iac_s')->label(__('main.aic'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('saa_s')->label(__('form.saa'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_s')->label(__('form.eac'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\Toggle::make('tec_s')->label(__('form.tec'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('tga_s')->label(__('form.tga'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ftg_s')->label(__('form.ftg'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('sta_s')->label(__('form.sta'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('pa_s')->label(__('form.pa'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ecl_s')->label(__('form.ecl'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ss_s')->label(__('form.ss'))
                                            ->required()->default(false)->inline(false),
                                    ]),
                                Forms\Components\Section::make('Premium')->columns($cgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('maxtv')->label(__('form.tim').'s Exam.')
                                            ->required()->numeric()->default(240),
                                        Forms\Components\TextInput::make('maxv')->label(__('form.tqu'))
                                            ->required()->numeric()->default(100),
                                        Forms\Components\TextInput::make('maxev')->label(__('form.equ'))
                                            ->required()->numeric()->default(200),
                                        Forms\Components\TextInput::make('iac_p')->label(__('main.aic'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('saa_p')->label(__('form.saa'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_p')->label(__('form.eac'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\Toggle::make('tec_p')->label(__('form.tec'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('tga_p')->label(__('form.tga'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ftg_p')->label(__('form.ftg'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('sta_p')->label(__('form.sta'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('pa_p')->label(__('form.pa'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ecl_p')->label(__('form.ecl'))
                                            ->required()->default(false)->inline(false),
                                        Forms\Components\Toggle::make('ss_p')->label(__('form.ss'))
                                            ->required()->default(false)->inline(false),
                                        ]),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('form.ai'))
                            ->schema([
                                Forms\Components\Section::make(__('form.apis'))->columns(3)
                                    ->schema([
                                        Password::make('apk')->label(__('form.apik'))
                                            ->required()->columnSpanFull()
                                            ->dehydrateStateUsing(fn (string $state): string => $state != Info::first()->apk ? Crypt::encryptString($state) : $state)
                                            ->dehydrated(fn (?string $state): bool => filled($state)),
                                        Forms\Components\TextInput::make('endp')->label(__('form.enu'))
                                            ->required()->rules(['url'])->columnSpan(2),
                                        Forms\Components\TextInput::make('model')->label(__('form.aimo'))
                                            ->required()->rules(['max:255']),
                                        Forms\Components\TextInput::make('endp2')->label(__('form.enu2'))
                                            ->required()->rules(['url'])->columnSpan(2),
                                        Forms\Components\TextInput::make('model2')->label(__('form.aimo2'))
                                            ->required()->rules(['max:255']),
                                        Forms\Components\TextInput::make('aivo')->label('Coach Ben')
                                            ->required()->rules(['max:200']),
                                        Forms\Components\TextInput::make('aivo2')->label('Coach Becky')
                                            ->required()->rules(['max:200']),
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
                                        Forms\Components\Textarea::make('cont4')->label(__('form.cont4'))
                                            ->required(),
                                        Forms\Components\Textarea::make('cont5')->label(__('form.cont5'))
                                            ->required(),
                                        Forms\Components\Textarea::make('cont6')->label(__('form.cont6'))
                                            ->required(),
                                        Forms\Components\Textarea::make('cont7')->label(__('form.cont7'))
                                            ->required(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Stripe')
                            ->schema([
                                Forms\Components\Section::make(__('form.cos'))->columns(3)
                                    ->schema([
                                        Password::make('spk')->label(__('form.sik'))
                                            ->required()->columnSpanFull()
                                            ->dehydrateStateUsing(fn (string $state): string => $state != Info::first()->spk ? Crypt::encryptString($state) : $state)
                                            ->dehydrated(fn (?string $state): bool => filled($state)),
                                        Password::make('whk')->label(__('form.whk'))
                                            ->required()->columnSpanFull()
                                            ->dehydrateStateUsing(fn (string $state): string => $state != Info::first()->whk ? Crypt::encryptString($state) : $state)
                                            ->dehydrated(fn (?string $state): bool => filled($state)),
                                    ]),
                                Forms\Components\Section::make('Basic')->columns($sgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('bp_id')->label(__('form.pid'))->required(),
                                        Forms\Components\TextInput::make('bp_ml')->label(__('form.mli'))->required(),
                                        Forms\Components\TextInput::make('bp_yl')->label(__('form.ali'))->required(),
                                        Forms\Components\TextInput::make('bp_amm')->label(__('form.amm'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('bp_amy')->label(__('form.amy'))
                                            ->required()->numeric()->default(0),
                                    ]),
                                Forms\Components\Section::make('Standard')->columns($sgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('sp_id')->label(__('form.pid'))->required(),
                                        Forms\Components\TextInput::make('sp_ml')->label(__('form.mli'))->required(),
                                        Forms\Components\TextInput::make('sp_yl')->label(__('form.ali'))->required(),
                                        Forms\Components\TextInput::make('sp_amm')->label(__('form.amm'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('sp_amy')->label(__('form.amy'))
                                            ->required()->numeric()->default(0),
                                    ]),
                                Forms\Components\Section::make('Premium')->columns($sgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('pp_id')->label(__('form.pid'))->required(),
                                        Forms\Components\TextInput::make('pp_ml')->label(__('form.mli'))->required(),
                                        Forms\Components\TextInput::make('pp_yl')->label(__('form.ali'))->required(),
                                        Forms\Components\TextInput::make('pp_amm')->label(__('form.amm'))
                                            ->required()->numeric()->default(0),
                                        Forms\Components\TextInput::make('pp_amy')->label(__('form.amy'))
                                            ->required()->numeric()->default(0),
                                    ]),
                                Forms\Components\Section::make(__('main.aic'))->columns($sgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('iac1_id')->label(__('form.pid').' 1')->required(),
                                        Forms\Components\TextInput::make('iac1_li')->label(__('form.pli').' 1')
                                            ->required(),
                                        Forms\Components\TextInput::make('iac1_qt')->label(__('form.qty').' 1')
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('iac1_am')->label(__('form.amo').' 1')
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('iac2_id')->label(__('form.pid').' 2')->required(),
                                        Forms\Components\TextInput::make('iac2_li')->label(__('form.pli').' 2')
                                            ->required(),
                                        Forms\Components\TextInput::make('iac2_qt')->label(__('form.qty').' 2')
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('iac2_am')->label(__('form.amo').' 2')
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('iac3_id')->label(__('form.pid').' 3')->required(),
                                        Forms\Components\TextInput::make('iac3_li')->label(__('form.pli').' 3')
                                            ->required(),
                                        Forms\Components\TextInput::make('iac3_qt')->label(__('form.qty').' 3')
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('iac3_am')->label(__('form.amo').' 3')
                                            ->required()->numeric()->default(0),
                                    ]),
                                Forms\Components\Section::make(__('form.eac'))->columns($sgrid)
                                    ->schema([
                                        Forms\Components\TextInput::make('eca_id')->label(__('form.pid'))->required(),
                                        Forms\Components\TextInput::make('eca_li')->label(__('form.pli'))
                                            ->required(),
                                        Forms\Components\TextInput::make('eca_qt')->label(__('form.qty'))
                                            ->required()->numeric()->default(1),
                                        Forms\Components\TextInput::make('eca_am')->label(__('form.amo'))
                                            ->required()->numeric()->default(1),
                                    ]),

                            ]),
                    ]),
            ]);
    }

    public function create(): void
    {
        $this->info->update($this->form->getState());
        Notification::make()->title(__('form.e30'))->success()->send();
        if ($this->info->wasChanged()) {
            \App\Models\Journ::add(auth()->user(), 'Settings', 3, 'Settings was changed');
        }
        cache()->forget('settings');
        cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
    }
}
