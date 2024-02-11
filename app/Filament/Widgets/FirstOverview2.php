<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vague;
use App\Models\Course;
use App\Models\Module;
use App\Models\Question;

class FirstOverview2 extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'sm' => 1,
        'md' => 2,
        'xl' => 3,
    ];
    protected static ?string $pollingInterval = null;
    public static function canView(): bool
    {
       // return true;
       return auth()->user()->ex>=2;
    }

    protected function getStats(): array
    {
        $bg = array();
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
        return $bg;
    }
}
