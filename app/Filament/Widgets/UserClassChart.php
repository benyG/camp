<?php

namespace App\Filament\Widgets;

use App\Models\Vague;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;

class UserClassChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    public function getColumns(): int|string|array
    {
        return 1;
    }
    public function getHeading(): ?string
    {
        return __('main.w34');
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0;
    }

    protected function getData(): array
    {
        $uc = [[], [], []];
        $us = Vague::select('name')->withCount('users')->get();
        foreach ($us as $val) {
            // dd($val);
            if ($val->users_count > 0) {
                $uc[0][] = $val->name;
                $uc[1][] = $val->users_count;
                $uc[2][] = $this->dynColors();
            }
        }

        $mpo=collect($uc[1])->sum();
        foreach ($uc[1] as $key => $value) {
            $uc[0][$key]=$uc[0][$key].' ('.round(100 * $value / ($mpo > 0 ? $mpo : 1), 2).'%)';
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
        return 'doughnut';
    }

    public function dynColors(): string
    {
        $col = '#';
        $ar = ['A', 'B', 'C', 'D', 'E', 'F', '0', '1', '2', '3', '4',
            '5', '6', '7', '8', '9'];
        for ($i = 0; $i < 6; $i++) {
            $col .= Arr::random($ar);
        }

        return $col;

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
