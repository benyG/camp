<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

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
    public function getTitle(): string|Htmlable
    {
        return __('main.m19');
    }

    public function getHeading(): string
    {
        return __('main.m19');
    }

}
