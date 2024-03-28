<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function (Model $record, $data) {
                $record->markEmailAsVerified();
                $txt = "New User created ! <br>
                Name: $record->name <br>
                Email: $record->email <br>
                Type: ".match (intval($data['ex'])) {
                    0 => 'S. Admin',
                    1 => 'Admin', 2 => 'Starter', 3 => 'User', 4 => 'Pro', 5 => 'VIP'
                }." <br>
                Timezone: $record->tz <br>
                Class: ".implode(',', $record->vagues()->pluck('name')->toArray());
                \App\Models\Journ::add(auth()->user(), 'Users', 1, $txt);

            })
                ->createAnother(false),
        ];
    }
}
