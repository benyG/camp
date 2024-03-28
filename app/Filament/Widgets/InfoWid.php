<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InfoWid extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.filament-info';

    public static function canView(): bool
    {
        return false;
    }
}
