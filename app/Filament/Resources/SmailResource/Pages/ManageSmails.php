<?php

namespace App\Filament\Resources\SmailResource\Pages;

use App\Filament\Resources\SmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSmails extends ManageRecords
{
    protected static string $resource = SmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getHeading(): string
    {
        return 'Inbox';
    }
}
