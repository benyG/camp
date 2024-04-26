<?php

namespace App\Filament\Widgets;

use App\Models\Question;
use App\Models\User;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class UserCourseChart5 extends ChartWidget
{
    protected static ?string $heading = 'Performance evolution';

    protected static string $view = 'filament.widgets.uc5';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 3;

    #[Locked]
    public $record;

    public $cs = 0;

    public $cs2 = 0;

    #[Locked]
    public $cos;

    public function mount($usrec = null): void
    {
        $this->record = is_int($usrec) ? User::with('exams2')->findOrFail($usrec) : auth()->user();
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
        $this->record = $this->record ?? auth()->user();
        $exa = $this->record->exams2()->where('certi', $this->cs)->whereIn('type', $arx)->limit($ix->taff)->latest('added_at')->get();
        foreach ($exa as $ex) {
            $md1 = 0;
            $md2 = 0;
            $uc[0][] = $ex->name;
            if (! empty($ex->pivot->gen) && is_array($ex->pivot->gen)) {
                $res = $ex->pivot->gen;
                $arrk = array_keys($ex->pivot->gen);
                $qrr = [];
                $rt = Question::whereIn('id', $arrk)->get();
                foreach ($rt as $quest) {
                    $bm = $quest->answers()->where('isok', true)->count() <= 1;
                    if ($bm) {
                        $ab = $quest->answers()->where('isok', true)->where('answers.id', $res[$quest->id][0])->count();
                        if ($ab > 0) {
                            $md1++;
                        } else {
                            $md2++;
                        }
                    } else {
                        $ab2 = $quest->answers()->where('isok', false)->whereIn('answers.id', $res[$quest->id])->count();
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
        $uc[1]=array_reverse($uc[1]);
        $uc[2]=array_reverse($uc[2]);
        $uc[0]=array_reverse($uc[0]);

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
                    x: {
                        grid: {
                            display: true,
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
