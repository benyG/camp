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
             /*Actions\Action::make('e')->label(__('main.m6'))->color('info')
                ->url(fn (): string => $this->getResource()::getUrl('approve')),*/
             Actions\CreateAction::make()
                ->after(function ($data) {
                    $txt = 'New Certification created ! <br>
                Name: '.$data['name'].' <br>
                Description: '.$data['descr'].' <br>
                ';
                    \App\Models\Journ::add(auth()->user(), 'Certifications', 1, $txt);
                }),
        ];
    }
}
