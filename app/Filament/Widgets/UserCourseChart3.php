<?php

namespace App\Filament\Widgets;

use App\Models\Module;
use App\Models\Question;
use App\Models\User;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class UserCourseChart3 extends ChartWidget
{
    protected static string $view = 'filament.widgets.uc3';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '250px';

    protected static ?int $sort = 1;

    #[Locked]
    public $record;

    public $cs = 0;

    public $mod = 0;

    #[Locked]
    public $cos;

    public function mount($usrec = null): void
    {
        $this->record = is_int($usrec) ? User::with('exams2')->findOrFail($usrec) : auth()->user();
    }

    public function getHeading(): ?string
    {
        return '% '.__('main.w35');
    }

    public static function canView(): bool
    {
        return auth()->user()->ex > 1;
    }

    #[On('cs-upd')]
    public function csupdated($csu)
    {
        $this->cs = intval($csu);
        $this->cos = Module::where('course', $this->cs)->get();
        $this->mod = 0;
        $this->updateChartData();
    }

    public function updatedMod()
    {
        $this->updateChartData();
    }

    protected function getFilters(): ?array
    {
        $arr = [0 => 'All'];

        return empty($this->cos) ? [] : array_merge($arr, $this->cos->pluck('name', 'id')->toArray());
    }

    protected function getData(): array
    {
        $uc = [[], [], []];
        if (! empty($this->cos) > 0) {
            //  $this->record = $this->record ?? auth()->user();
            $exa = $this->record->exams2()->where('certi', $this->cs)->get();
            $md1 = 0;
            $md2 = 0;
            $QUEST = Question::select('id', 'module')->with('answers')->get();
            foreach ($exa as $ex) {
                if (! empty($ex->pivot->gen) && is_array($ex->pivot->gen)) {
                    $res = $ex->pivot->gen;
                    $arrk = array_keys($ex->pivot->gen);
                    $qrr = [];
                    $rt = $this->mod == 0 ? $QUEST->whereIn('id', $arrk) :
                    $QUEST->whereIn('id', $arrk)->where('module', $this->mod);
                    foreach ($rt as $quest) {
                        $bm = $quest->answers->sum(function (\App\Models\Answer $aas) {
                            return $aas->qa->isok == 1 ? 1 : 0;
                        }) <= 1;
                        if ($bm) {
                            $ab = $quest->answers->where('id', $res[$quest->id][0])->sum(function (\App\Models\Answer $aas) {
                                return $aas->qa->isok == 1 ? 1 : 0;
                            });
                            if ($ab > 0) {
                                $md1++;
                            } else {
                                $md2++;
                            }
                        } else {
                            $ab2 = $quest->answers->whereIn('id', $res[$quest->id])->sum(function (\App\Models\Answer $aas) {
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
            }
            $uc[0][] = 'Good Answers';
            $uc[1][] = $md1;
            $uc[2][] = '#00FF00';

            $uc[0][] = 'Bad Answers';
            $uc[1][] = $md2;
            $uc[2][] = '#FF0000';
        }
        $mpo = collect($uc[1])->sum();
        foreach ($uc[1] as $key => $value) {
            $uc[0][$key] = $uc[0][$key].' ('.round(100 * $value / ($mpo > 0 ? $mpo : 1), 2).'%)';
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
        return RawJs::make(<<<'JS'
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
