<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vague;
use App\Models\Course;
use App\Models\Module;
use App\Models\Question;

class FirstOverview extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 2,
        'xl' => 3,
    ];
    protected function getStats(): array
    {
        $bg = array();
        $bg[]= Stat::make('Nb. Classes', Vague::all()->count())
        //    ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        $bg[]= Stat::make('Nb. Courses', Course::all()->count())
        //    ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        $bg[]= Stat::make('Nb. Modules', Module::all()->count())
        //    ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        $bg[]= Stat::make('Nb. Questions', Question::all()->count())
        //    ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        return $bg;
    }
}
