<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Filament\Support\RawJs;
use App\Models\Module;
use App\Models\ExamUser;
use App\Models\Exam;
use App\Models\Question;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class UserCourseChart4 extends ChartWidget
{
    protected static ?string $heading = 'Tests/Exams Launched';
    protected static string $view = 'filament.widgets.uc4';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 3;
    public $record;
    public $cs=0;
    public $mod=0;
    #[Locked]
    public $cos;

    public static function canView(): bool
    {
        return auth()->user()->ex>1 && Course::has('users1')->where('pub',true)->count()>0;
    }
    #[On('cs-upd')]
    public function csupdated($csu){
      //  dd('dd');
        $this->cs=intval($csu);
        $this->updateChartData();
    }
    protected function getData(): array
    {
        $uc=[array(),array(),array()];
        $exx=Exam::where('certi',$this->cs)->get()->pluck('id')->toArray();
            $exa=ExamUser::selectRaw('DATE(added) as ax,COUNT(*) as exa')
            ->where('user',auth()->id())->whereIn('exam',$exx)->
            groupBy(DB::raw('DATE(added)'))->latest('ax')->get();
           // dd($exa->count());
            foreach ($exa as $ex) {
            $md1=0;$md2=0;
                $uc[0][]=$ex->ax;
                $uc[2][]=$this->dynColors();
                $uc[1][]=$ex->exa;
            }
        return [
            'datasets' => [
                [
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
        return 'bar';
    }
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        grid: {
                            display: true,
                        },
                        ticks: {
                            display: true,
                        },
                    },
                    x: {
                        grid: {
                            display: true,
                        },
                        ticks: {
                            display: true,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    }
                }
            }
        JS);
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
}
