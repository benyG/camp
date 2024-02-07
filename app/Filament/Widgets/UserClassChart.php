<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use App\Models\Vague;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Filament\Support\RawJs;

class UserClassChart extends ChartWidget
{
    protected static ?string $heading = 'Users per Class';
    protected static ?string $pollingInterval = null;
    public static function canView(): bool
    {
        return auth()->user()->ex==0;
    }
    protected function getData(): array
    {
        $uc=[array(),array(),array()];
        $us=Vague::withCount('users')->get();
        foreach ($us as $val) {
            // dd($val);
            if($val->users_count>0){
                    $uc[0][]=$val->name;
                    $uc[1][]=$val->users_count;
                    $uc[2][]=$this->dynColors();
            }
        }
       // dd($uc[2]);
        return [
            'datasets' => [
                [
                   // 'label' => 'Blog posts created',
                    'data' => $uc[1],
                    'backgroundColor' => $uc[2],
                    //'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $uc[0],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
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
