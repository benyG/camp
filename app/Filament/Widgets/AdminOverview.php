<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminOverview extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = true;

    protected static string $view = 'filament.widgets.adm-ovv';
    protected int | string | array $columnSpan = 'full';

    #[Locked]
    public $va;

    #[Locked]
    public $co;
    #[Locked]
    public $pro;

    #[Locked]
    public $mo;

    #[Locked]
    public $qu;

    #[Locked]
    public $us;

    public function mount()
    {

        $this->va = \App\Models\Vague::count();
        $this->pro = \App\Models\Prov::count();
        $this->co = \App\Models\Course::count();
        $this->mo = \App\Models\Module::count();
        $this->qu = \App\Models\Question::count();
        $this->us = \App\Models\User::count();
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0 || auth()->user()->ex == 1;
    }
}
