<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Notifications\Notification;
use Filament\Widgets\ChartWidget;

class IacWid extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static string $view = 'filament.widgets.iac-wid';

    protected static ?int $sort = 9;

    protected static ?string $maxHeight = '500px';

    #[Locked]
    public $iac;

    #[Locked]
    public $max;

    #[Locked]
    public $ang;

    public function mount(): void
    {
        $this->iac = \App\Models\Info::first()->iac;
        $this->max = \App\Models\Info::first()->mia;
        $this->ang = intval($this->iac * 180 / $this->max);
        if ($this->ang > 180) {
            $this->ang = 180;
        }
    }

    public function getColumns(): int|string|array
    {
        return 1;
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0;
    }

    public function getHeading(): ?string
    {
        return __('main.w45').' /'.$this->max;
    }

    protected function getData(): array
    {
        $uc = [[], [], []];
        $us = Course::select('name')->withCount('exams')->get();
        foreach ($us as $val) {
            if ($val->exams_count > 0) {
                $uc[0][] = $val->name;
                $uc[1][] = $val->exams_count;
                $uc[2][] = dynColors();
            }
        }

        $mpo = collect($uc[1])->sum();
        foreach ($uc[1] as $key => $value) {
            $uc[0][$key] = $uc[0][$key].' ('.round(100 * $value / ($mpo > 0 ? $mpo : 1), 2).'%)';
        }

        return [
        ];
    }

    public function priAction()
    {
        \App\Models\Info::where('id', 1)->update(['iac' => 0]);
        $this->iac = 0;
        Notification::make()->title(__('form.e30'))->success()->send();
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
