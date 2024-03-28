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
            Actions\CreateAction::make()->after(function ($data) {
                $txt = 'New Class created ! <br>
                Name: '.$data['name'].' <br>
                ';
                \App\Models\Journ::add(auth()->user(), 'Classes', 1, $txt);
            }),
        ];
    }
}
