<?php

namespace App\Filament\Resources\JournResource\Pages;

use App\Filament\Resources\JournResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJourns extends ManageRecords
{
    protected static string $resource = JournResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
