<?php

namespace App\Filament\Widgets;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class UserCourseChart5 extends ChartWidget
{
    protected static string $view = 'filament.widgets.uc5';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 3;

    public $cs = 0;

    public $cs2 = 0;

    #[Locked]
    public $cos;
    #[Locked]
    public $usrec;

    public function mount($usrec = null): void
    {
        if(!is_null($usrec)) $this->usrec =$usrec;
    }

    public function getHeading(): ?string
    {
        return __('main.w39');
    }

    public static function canView(): bool
    {
        return auth()->user()->ex > 1;
    }

    #[On('cs-upd')]
    public function csupdated($csu)
    {
        //  dd('dd');
        $this->cs = intval($csu);
        $this->updateChartData();
    }

    public function updatedCs2()
    {
        $this->updateChartData();
    }

    protected function getFilters(): ?array
    {
        return ['0' => 'All', '1' => 'Test', '2' => 'Exam'];
    }

    protected function getData(): array
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $arx = $this->cs2 == '0' ? [0, 1] : [intval($this->cs2) - 1];
        $uc = [[], [], []];
        $usx = !is_null($this->usrec) ? User::findOrFail($this->usrec) : auth()->user();
        $usx->loadMissing('exams2');
        $exa = $usx->exams2->where('certi', $this->cs)->whereIn('type', $arx)->take($ix->taff);
        $QUEST = Question::select('id')->with('answers')->get();
        foreach ($exa as $ex) {
            $md1 = 0;
            $md2 = 0;
            $uc[0][] = $ex->name;
            if (! empty($ex->pivot->gen) && is_array($ex->pivot->gen)) {
                $res = $ex->pivot->gen;
                $arrk = array_keys($ex->pivot->gen);
                $qrr = [];
                $rt = $QUEST->whereIn('id', $arrk);
                foreach ($rt as $quest) {
                    $bm = $quest->answers->sum(function (Answer $aas) {
                        return $aas->qa->isok == 1 ? 1 : 0;
                    }) <= 1;
                    if ($bm) {
                        $ab = $quest->answers->where('id', $res[$quest->id][0])->sum(function (Answer $aas) {
                            return $aas->qa->isok == 1 ? 1 : 0;
                        });
                        if ($ab > 0) {
                            $md1++;
                        } else {
                            $md2++;
                        }
                    } else {
                        $ab2 = $quest->answers->whereIn('id', $res[$quest->id])->sum(function (Answer $aas) {
                            return $aas->qa->isok == 0 ? 1 : 0;
                        });
                        if ($ab2 == 0) {
                            $md1++;
                        } else {
                            $md2++;
                        }
                    }
                }
            }
            $uc[1][] = $md1;
            $uc[2][] = $md2;
        }
        $uc[1] = array_reverse($uc[1]);
        $uc[2] = array_reverse($uc[2]);
        $uc[0] = array_reverse($uc[0]);

        return [
            'datasets' => [
                [
                    'label' => 'Good Answers',
                    'data' => $uc[1],
                    'backgroundColor' => '#00FF00',
                    'borderColor' => '#00FF00',
                ],
                [
                    'label' => 'Bad Answers',
                    'data' => $uc[2],
                    'backgroundColor' => '#FF0000',
                    'pointBackgroundColor' => '#FF0000',
                    'borderColor' => '#FF0000',
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
        return RawJs::make(<<<'JS'
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
                    y: {
                        ticks: {
                            stepSize: 1
                        },
                    },
                    x: {
                        grid: {
                            display: true,
                        },
                        ticks: {
                            display: false,
                        },
                    },
                },
                elements: {
                    line: {
                        tension: 0.5,
                    }
                }
            }
        JS);
    }
}
