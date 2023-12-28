<?php

namespace App\Filament\Resources\SmailResource\Pages;

use App\Filament\Resources\SmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\Imail;
use Filament\Notifications\Notification;

class ManageSmails extends ManageRecords
{
    protected static string $resource = SmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                $data['from'] = auth()->id();
                return $data;
            })->after(function(Model $record){
                foreach ($record->users as $rec) {
                Mail::to($rec->email)->send(new Imail($record,$rec->name,$rec->email));
                Notification::make()->success()->title('Successfully sent via SMTP to : '.$rec->email)->send();
                }
            }),
        ];
    }
    public function getHeading(): string
    {
        return 'Inbox';
    }
}
