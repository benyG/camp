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
        $this->items = auth()->user()->ex == 0 ? \App\Models\Ann::where('hid', true)->whereDate('due', '>', now())->get()->toArray() :
        \App\Models\Ann::where('hid', true)->whereDate('due', '>', now())->where('type', 'like', '%'.auth()->user()->ex.'%')->get()->toArray();
    }
}
