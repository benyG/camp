<?php

namespace App\Filament\Resources\ProvResource\Pages;

use App\Filament\Resources\ProvResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProvs extends ManageRecords
{
    protected static string $resource = ProvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($data) {
                $txt = 'New Provider created ! <br>
            Name: '.$data['name'].' <br>
            ';
                \App\Models\Journ::add(auth()->user(), 'Providers', 1, $txt);
            }),
        ];
    }
}
