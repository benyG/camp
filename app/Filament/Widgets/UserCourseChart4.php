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
    protected static ?string $heading = 'Assess. created';
    protected static string $view = 'filament.widgets.uc4';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 3;
    public $cs=0;
    public $cs2='X';
    #[Locked]
    public $cos;
    #[Locked]
    public $record;

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
        $this->updateChartData();
    }
    public function updatedCs2()
    {
        $this->updateChartData();
    }
    protected function getFilters(): ?array
    {
        return ['X'=>'All','0' => 'Test','1'=>'Exam'];
    }
    protected function getData(): array
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $uc=[array(),array(),array()];
        $this->record=$this->record??auth()->user();
        $arx=$this->cs2=='X'?[0,1]:[intval($this->cs2)];
        $exx=Exam::where('certi',$this->cs)->whereIn('type',$arx)->limit($ix->taff)->get()->pluck('id')->toArray();
            $exa=ExamUser::selectRaw('DATE(added) as ax,COUNT(*) as exa')
            ->where('user',$this->record->id)->whereIn('exam',$exx)->
            groupBy(DB::raw('DATE(added)'))->oldest('ax')->get();
           // dd($exa->count());
            foreach ($exa as $ex) {
            $md1=0;$md2=0;
                $uc[0][]=$ex->ax;
                $uc[1][]=$ex->exa;
            }
        return [
            'datasets' => [
                [
                    'data' => $uc[1],
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#249e77',
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
