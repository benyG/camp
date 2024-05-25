<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SubWid extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = true;

    protected static string $view = 'filament.widgets.sub-wid';

    #[Locked]
    public $items;

    public function mount()
    {
    }
    public static function canView(): bool
    {
      return false;
     // return auth()->user()->ex > 1 && (is_null(auth()->user()->sub) || now()>auth()->user()->sub->exp);
    }
}
