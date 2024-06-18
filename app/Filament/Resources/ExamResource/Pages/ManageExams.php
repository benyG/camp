<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExams extends ManageRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return [
            Actions\Action::make('ddr')->label(__('form.cre'))
                ->visible(fn (): bool => auth()->user()->can('add-exam'))
                ->url(fn (): string => $this->getResource()::getUrl('create'))
                ->color('primary'),
            Actions\Action::make('ddr14')->label(__('form.cre'))
                ->visible(fn (): bool => auth()->user()->cannot('add-exam'))
                ->modalSubmitActionLabel(__('form.upg'))
                ->modalHeading(__('form.upp'))
                ->closeModalByClickingAway(false)
                ->modalDescription(__('form.saa3'))
                ->color('primary'),
        ];
    }
}
