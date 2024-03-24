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
            Actions\Action::make(__('form.res'))
                ->action(function () {
                $this->form->fill();
                    Notification::make()
                        ->title(__('form.e5'))
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
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        if($record->wasChanged()){
           \App\Models\Journ::add(auth()->user(),'Settings',3,"Settings was changed");
        }

        return $record;
    }
}
