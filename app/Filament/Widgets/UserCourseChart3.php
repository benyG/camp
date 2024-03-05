<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Filament\Support\RawJs;
use App\Models\Module;
use App\Models\Question;
use Livewire\Attributes\On;

class UserCourseChart3 extends ChartWidget
{
    protected static ?string $heading = '% Good/Bad Ans.';
    protected static string $view = 'filament.widgets.uc3';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '250px';
    protected static ?int $sort = 1;

    #[Locked]
    public $record;
    public $cs=0;
    public $mod=0;
    #[Locked]
    public $cos;
    public function mount($usrec=null): void
    {
        $this->record=is_int($usrec)?User::with('exams2')->findOrFail($usrec):auth()->user();
    }

    public static function canView(): bool
    {
        return auth()->user()->ex>1;
    }
    #[On('cs-upd')]
    public function csupdated($csu){
      //  dd('dd');
        $this->cs=intval($csu);
        $this->cos=Module::where('course',$this->cs)->get();
        $this->mod=$this->cos->count()>0?$this->cos->first()->id:0;
        $this->updateChartData();
    }
    public function updatedMod()
    {
        $this->updateChartData();
    }

    protected function getFilters(): ?array
    {
        return empty($this->cos)? array(): $this->cos->pluck("name",'id')->toArray();
    }
    protected function getData(): array
    {
        $uc=[array(),array(),array()];
        if(!empty($this->cos)>0){
            $this->record=$this->record??auth()->user();
            $exa=$this->record->exams2()->where('certi',$this->cs)->get();
            $md1=0;$md2=0;
            foreach ($exa as $ex) {
                if(!empty($ex->pivot->gen) && is_array($ex->pivot->gen)){
                    $res=$ex->pivot->gen;
                    $arrk=array_keys($ex->pivot->gen);
                    $qrr=array();
                    $rt=Question::whereIn('id',$arrk)->where('module',$this->mod)->get();
                    foreach ($rt as $quest) {
                        $bm=$quest->answers()->where('isok',true)->count()<=1;
                        if($bm){
                            $ab=$quest->answers()->where('isok',true)->where('answers.id',$res[$quest->id][0])->count();
                            if($ab>0) {
                                $md1++;
                            }else $md2++;
                        }else{
                            $ab2=$quest->answers()->where('isok',false)->whereIn('answers.id',$res[$quest->id])->count();
                            if($ab2==0) {
                                $md1++;
                            }else $md2++;
                        }
                    }
                }
            }
            $uc[0][]="Good Answers";
            $uc[1][]=$md1;
            $uc[2][]="#00FF00";

            $uc[0][]="Bad Answers";
            $uc[1][]=$md2;
            $uc[2][]="#FF0000";

        }
        return [
            'datasets' => [
                [
                   // 'label' => 'Blog posts created',
                    'data' => $uc[1],
                    'backgroundColor' => $uc[2],
                    'borderColor' => $uc[2],
                ],
            ],
            'labels' => $uc[0],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },
            }
        JS);
    }
}
