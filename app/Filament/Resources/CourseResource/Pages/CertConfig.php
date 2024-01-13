<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\Course;
use App\Models\Module;
use App\Models\CertConfig as CertConf;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class CertConfig extends Page
{
    use InteractsWithRecord;
    protected static string $resource = CourseResource::class;
    public ?array $data = [];
    protected static string $view = 'filament.resources.course-resource.pages.cert-config';
    protected static ?string $title = 'Certifications Configurations';
    protected ?string $subheading = 'Here you can set the TYPICAL configuration for a given certification.';
    public ?string $timer = null;
    public ?string $quest = null;
    public ?array $mods = null;
    public bool $ie=false;
public ?string $content = null;
    public function mount(int | string $record): void
    {
        abort_unless(auth()->user()->ex==0, 403);
        static::authorizeResourceAccess();
        $this->record = $this->resolveRecord($record);
        $ee=CertConf::where('course',$record)->get();
        $ie=$this->ie=$ee->count()>0;
        $this->form->fill($ie?$ee->first()->toArray():null);
    }
    public function form(Form $form): Form
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        return $form
            ->schema([
                Forms\Components\Placeholder::make('created')->label('Certification')
                ->content(new HtmlString('<span class="text-lg font-bold">'.$this->record->name.'<span>')),
                Forms\Components\Section::make('General settings')->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('timer')->numeric()->step(5)->requiredIf('type', '1')->label('Timer (min)')
                    ->rules(['numeric']),
                    Forms\Components\TextInput::make('quest')->numeric()->step(5)->required()->label('Nb. Questions')
                    ->rules(['numeric']),
                ]),
                Forms\Components\Section::make('Module selection')
                ->schema([
                    Forms\Components\Repeater::make('mods')->grid(2)->label('Modules Configuration')
                    ->addActionLabel('Add a Module')->reorderable(false)
                    ->schema([
                        Forms\Components\Select::make('module')->label('Name')
                        ->options(Module::where('course',$this->record->id)->pluck('name','id'))->required()
                        ->preload()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        Forms\Components\TextInput::make('nb')->numeric()->step(5)->required()->label('Questions')->rules(['numeric'])
                        ->default($ix->minq),
                    ])->minItems(1)->maxItems(Module::where('course',$this->record->id)->count())
                ]),
            ])->model(CertConf::class);
    }
    public function create(): void
    {
        if($this->ie){
            $cert=CertConf::where('course',$this->record->id)->first();
            $cert->timer=$this->timer;
            $cert->quest=$this->quest;
            $cert->mods=$this->mods;
        }else{
            $cert=new CertConf();
            $cert->timer=$this->timer;
            $cert->quest=$this->quest;
            $cert->mods=$this->mods;
            $cert->course=$this->record->id;
        }
        $cert->save();
        Notification::make()->success()->title('Config saved.')->send();
    }
}
