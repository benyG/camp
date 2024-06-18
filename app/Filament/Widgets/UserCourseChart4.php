<?php

namespace App\Filament\Widgets;

use App\Models\Exam;
use App\Models\ExamUser;
use App\Models\User;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class UserCourseChart4 extends ChartWidget
{
    protected static string $view = 'filament.widgets.uc4';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 3;

    public $cs = 0;

    public $cs2 = 'X';

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
        return __('main.w38');
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
        return ['X' => 'All', '0' => 'Test', '1' => 'Exam'];
    }

    protected function getData(): array
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $uc = [[], [], []];
        $rec = !is_null($this->usrec) ? User::findOrFail($this->usrec) : auth()->user();
        $arx = $this->cs2 == 'X' ? [0, 1] : [intval($this->cs2)];
        $exx = Exam::select('id', 'type', 'added_at', 'certi')->where('certi', $this->cs)->whereIn('type', $arx)->latest('added_at')->get()->pluck('id')->toArray();
        $exa = ExamUser::selectRaw('DATE(added) as ax,COUNT(*) as exa')
            ->where('user', $rec->id)->whereIn('exam', $exx)->limit($ix->taff)->
        groupBy(DB::raw('DATE(added)'))->latest('ax')->get();
        // dd($exa->count());
        foreach ($exa as $ex) {
            $uc[0][] = $ex->ax;
            $uc[1][] = $ex->exa;
        }
        $uc[1] = array_reverse($uc[1]);
        $uc[0] = array_reverse($uc[0]);

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
        return RawJs::make(<<<'JS'
            {
                scales: {
                    y: {
                        grid: {
                            display: true,
                        },
                        ticks: {
                            display: true,
                            stepSize: 1
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
}
