<?php

namespace App\Filament\Resources\InfoResource\Pages;

use App\Filament\Resources\InfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class EditInfo extends EditRecord
{
    protected static string $resource = InfoResource::class;

    protected function getFormActions(): array
    {
        return [...parent::getFormActions(),
            Actions\Action::make('reset')
                ->action(function () {
                $this->form->fill();
                    Notification::make()
                        ->title('The form has been reset')
                        ->success()
                        ->send();
                })
            ];
    }
    protected function afterSave(): void
    {
        cache()->forget('settings');
        cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
    }
}
