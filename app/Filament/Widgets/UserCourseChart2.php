<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Filament\Support\RawJs;

class UserCourseChart2 extends ChartWidget
{
    protected static ?string $heading = '% Questions answered per Module';
    protected static string $view = 'filament.widgets.uc2';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 0;
    public $cs=0;
    #[Locked]
    public $cos;

    public function mount(): void
    {
        $this->cos=Course::has('users1')->where('pub',true)->get();
        if($this->cos->count()>0) {
            $this->cs=$this->cos->first()->id;
            $this->dispatch('cs-upd',csu:$this->cs)->to(\App\Filament\Widgets\UserCourseChart3::class);
            $this->dispatch('cs-upd',csu:$this->cs)->to(\App\Filament\Widgets\UserCourseChart5::class);
            $this->dispatch('cs-upd',csu:$this->cs)->to(\App\Filament\Widgets\UserCourseChart4::class);
        }
    }
    public static function canView(): bool
    {
    //    return false;
      //  return auth()->user()->ex!=0 || isset($this->usrec);
      return auth()->user()->ex>1 && Course::has('users1')->where('pub',true)->count()>0;
    }
    protected function getFilters(): ?array
    {
        return $this->cos->pluck("name",'id')->toArray();
    }
    public function updatedCs()
    {
        $this->updateChartData();
        $this->dispatch('cs-upd',csu:$this->cs)->to(UserCourseChart3::class);
        $this->dispatch('cs-upd',csu:$this->cs)->to(UserCourseChart5::class);
        $this->dispatch('cs-upd',csu:$this->cs)->to(UserCourseChart4::class);
    }

    protected function getData(): array
    {
        $mod=Module::where('course',$this->cs)->get()->pluck('name','id')->toArray();
        $exa=auth()->user()->exams2()->where('certi',$this->cs)->get();
        $que=array();
        foreach ($exa as $ex) {
            if(!empty($ex->pivot->gen) && is_array($ex->pivot->gen)){
             $que=array_merge($que,array_keys($ex->pivot->gen));
            }
        }
        $que=Question::selectRaw('count(id) as quest, module')->whereIn('id',$que)->groupBy('module')->get()->pluck('quest','module')->toArray();
        $uc=[array(),array(),array()];
        foreach ($mod as $key => $mm) {
            $uc[0][]=$mm;
            $uc[1][]=array_key_exists($key,$que)? $que[$key]:0;
            $uc[2][]=$this->dynColors();
        }
      //  dd($uc);
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
    public function dynColors() : string {
        $col="#";
        $ar=['A','B','C','D','E','F','0','1','2','3','4',
        '5','6','7','8','9'];
        for ($i=0; $i < 6; $i++) {
           $col.=Arr::random($ar);
        }
        return $col;

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
