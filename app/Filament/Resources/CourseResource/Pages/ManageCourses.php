<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCourses extends ManageRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('e')->label('Approvals')->color('info')
            ->url(fn (): string => $this->getResource()::getUrl('approve')),
            Actions\CreateAction::make(),
        ];
    }
}
