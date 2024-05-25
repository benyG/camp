<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Pricing extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.pricing';
    public $ix;
    public function mount(){
        $this->ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
    }
}
