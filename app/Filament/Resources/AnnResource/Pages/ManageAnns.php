<?php

namespace App\Filament\Resources\AnnResource\Pages;

use App\Filament\Resources\AnnResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAnns extends ManageRecords
{
    protected static string $resource = AnnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
