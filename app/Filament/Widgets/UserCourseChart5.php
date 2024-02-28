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

class UserCourseChart5 extends ChartWidget
{
    protected static ?string $heading = 'Performance evolution';
    protected static string $view = 'filament.widgets.uc5';
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
            $exa=auth()->user()->exams2()->where('certi',$this->cs)->latest('added_at')->get();
            foreach ($exa as $ex) {
            $md1=0;$md2=0;
                $uc[0][]=$ex->name;
                if(!empty($ex->pivot->gen) && is_array($ex->pivot->gen)){
                    $res=$ex->pivot->gen;
                    $arrk=array_keys($ex->pivot->gen);
                    $qrr=array();
                    $rt=Question::whereIn('id',$arrk)->get();
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
                $uc[1][]=$md1;$uc[2][]=$md2;
            }
        return [
            'datasets' => [
                [
                    'label' => 'Good Answers',
                    'data' => $uc[1],
                    'borderColor' => "#00FF00",
                ],
                [
                    'label' => 'Bad Answers',
                    'data' => $uc[2],
                    'borderColor' => "#FF0000",
                ],

            ],
            'labels' => $uc[0],
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
            }
        JS);
    }
}
