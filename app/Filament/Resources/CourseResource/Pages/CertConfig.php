<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\CertConfig as CertConf;
use App\Models\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;

class CertConfig extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CourseResource::class;

    public ?array $data = [];

    protected static string $view = 'filament.resources.course-resource.pages.cert-config';

    protected static ?string $title = 'Configurations';

    public ?string $timer = null;

    public ?string $quest = null;

    public ?array $mods = null;

    public bool $ie = false;

    public ?string $content = null;

    public function getHeading(): string
    {
        return __('main.cc1');
    }

    public function getSubheading(): ?string
    {
        return __('main.cc2');
    }

    public function mount(int|string $record): void
    {
        abort_unless(auth()->user()->ex == 0, 403);
        static::authorizeResourceAccess();
        $this->record = $this->resolveRecord($record);
        $ee = CertConf::where('course', $record)->get();
        $ie = $this->ie = $ee->count() > 0;
        $this->form->fill($ie ? $ee->first()->toArray() : null);
    }

    public function form(Form $form): Form
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return $form
            ->schema([
                Forms\Components\Placeholder::make('created')->label('Certification')
                    ->content(new HtmlString('<span class="text-lg font-bold">'.$this->record->name.'<span>')),
                Forms\Components\Section::make(__('form.gs'))->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('timer')->numeric()->requiredIf('type', '1')->label(__('form.tim').' (min)')
                            ->rules(['numeric']),
                        Forms\Components\TextInput::make('quest')->numeric()->required()->label('Nb. Questions')
                            ->rules(['numeric']),
                    ]),
                Forms\Components\Section::make(__('main.cc3'))
                    ->schema([
                        Forms\Components\Repeater::make('mods')->grid(2)->label('')
                            ->addActionLabel(__('form.modad'))->reorderable(false)
                            ->schema([
                                Forms\Components\Select::make('module')->label('Module')
                                    ->options(Module::where('course', $this->record->id)->pluck('name', 'id'))->required()
                                    ->preload()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                Forms\Components\TextInput::make('nb')->numeric()->required()->label('Questions')->rules(['numeric'])
                                    ->default($ix->minq),
                            ])->minItems(1)->maxItems(Module::where('course', $this->record->id)->count()),
                    ]),
            ])->model(CertConf::class);
    }

    public function create(): void
    {
        $txt = '';
        if ($this->ie) {
            $cert = CertConf::where('course', $this->record->id)->first();
            if ($cert->timer != $this->timer) {
                $txt .= "Timer was changed from '$cert->timer' to '$this->timer' <br>";
            }
            if ($cert->quest != $this->quest) {
                $txt .= "Nb. of questions was changed from '$cert->quest' to '$this->quest' <br>";
            }
            if ($cert->mods[array_key_first($cert->mods)] != $this->mods[array_key_first($this->mods)]) {
                $txt .= 'Module configuration was changed';
            }
            $cert->timer = $this->timer;
            $cert->quest = $this->quest;
            $cert->mods = $this->mods;
        } else {
            $cert = new CertConf();
            $cert->timer = $this->timer;
            $cert->quest = $this->quest;
            $cert->mods = $this->mods;
            $cert->course = $this->record->id;
            $txt = "Cert. Config created ! <br>
            Timer: $cert->timer <br>
            Nb. Questions: $cert->quest <br>
            Module configuration: ".json_encode($cert->mods[array_key_first($cert->mods)]).' <br>
            Certification: '.$this->record->name.' <br>
            ';
        }
        $cert->save();
        if (strlen($txt) > 0) {
            \App\Models\Journ::add(auth()->user(), 'Cert. Config', $this->ie ? 3 : 1, $txt);
        }

        Notification::make()->success()->title('Config saved.')->send();
    }
}
