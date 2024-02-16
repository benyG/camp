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
    protected static ?string $pollingInterval = null;
    protected function getColumns(): int
    {
        return 4;
    }
    protected function getStats(): array
    {
        $bg = array();
        if(auth()->user()->ex<=1){
            $bg[]= Stat::make('Nb. Classes', Vague::count())
            //    ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success');
            $bg[]= Stat::make('Nb. Courses', Course::count())
            //    ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success');
            $bg[]= Stat::make('Nb. Modules', Module::count())
            //    ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success');
        $bg[]= Stat::make('Nb. Questions', Question::count())
        //    ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success');
        }
        return $bg;
    }
}
