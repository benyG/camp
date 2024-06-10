<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ConnWid extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static string $view = 'filament.widgets.conn-wid';

    protected static ?int $sort = 10;

    protected static ?string $maxHeight = '200px';

    #[Locked]
    public $mlc;

    public function mount(): void
    {
        $this->mlc = \App\Models\Info::first()->mlc;
    }

    public function getColumns(): int|string|array
    {
        return 1;
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0;
    }

    public function getHeading(): ?string
    {
        return __('main.w46').' /'.$this->mlc.' '.trans_choice('form.day', 5);
    }

    protected function getData(): array
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $uc = [[], [], []];
        $exa = \App\Models\Journ::selectRaw('DATE(created_at) as ax,COUNT(*) as exa')
            ->where('ac', 0)->limit(30)->
        groupBy(DB::raw('DATE(created_at)'))->latest('ax')->get();
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
                    'fill' => true,
                    'tension' => 0.5,
                    'borderColor' => '#fff33b',
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
                plugins: {
                    legend: {
                        display: false,
                    }
                }
            }
        JS);
    }
}
