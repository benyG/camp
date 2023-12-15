<?php

namespace App\Filament\Resources\VagueResource\Pages;

use App\Filament\Resources\VagueResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageVagues extends ManageRecords
{
    protected static string $resource = VagueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
