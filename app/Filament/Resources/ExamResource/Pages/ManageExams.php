<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\Course;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExams extends ManageRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        $oo = Course::has('users1')->where('pub', true)->count();

        return [
            Actions\CreateAction::make()
                ->disabled(fn (): bool => auth()->user()->ex == 0 ? false : $oo <= 0)
                ->color(fn (): string => $oo <= 0 ? 'gray' : 'primary'),
        ];
    }
}
