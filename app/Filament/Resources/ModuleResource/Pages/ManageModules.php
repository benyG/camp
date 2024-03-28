<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageModules extends ManageRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($record) {
                //  dd($record);
                $txt = "New module created ! <br>
                Name: $record->name <br>
                Certification: ".$record->courseRel->name.' <br>
                ';
                \App\Models\Journ::add(auth()->user(), 'Modules', 1, $txt);
            }),
        ];
    }
}
