<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Arr;

class UserCourseChart extends ChartWidget
{
    protected static ?string $heading = 'Users per Certifications';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    public function getColumns(): int|string|array
    {
        return 1;
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0;
    }

    protected function getData(): array
    {
        $uc = [[], [], []];
        $us = Course::withCount('users')->get();
        foreach ($us as $val) {
            // dd($val);
            if ($val->users_count > 0) {
                $uc[0][] = $val->name;
                $uc[1][] = $val->users_count;
                $uc[2][] = $this->dynColors();
            }
        }

        // dd($uc[2]);
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
