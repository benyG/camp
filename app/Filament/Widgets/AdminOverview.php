<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminOverview extends Widget
{
    protected static ?int $sort = -1;
    protected static bool $isLazy = true;
    protected static string $view = 'filament.widgets.adm-ovv';
   // protected int | string | array $columnSpan = 'full';

    #[Locked]
    public $va;
    #[Locked]
    public $co;
    #[Locked]
    public $mo;
    #[Locked]
    public $qu;
    #[Locked]
    public $iac;

    public function mount(){

        $this->va= \App\Models\Vague::count();
        $this->co= \App\Models\Course::count();
        $this->mo= \App\Models\Module::count();
        $this->qu= \App\Models\Question::count();
        $this->iac= \App\Models\User::all()->sum('ix');
    }
    public static function canView(): bool
    {
        return auth()->user()->ex==0 || auth()->user()->ex==1;
    }
}
