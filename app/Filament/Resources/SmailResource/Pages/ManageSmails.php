<?php

namespace App\Filament\Resources\SmailResource\Pages;

use App\Filament\Resources\SmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notif;
use Exception;

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
/*                 foreach ($record->users as $rec) {
                Mail::to($rec->email)->send(new Imail($record,[$rec->name,$rec->email],'1'));
                Notification::make()->success()->title('Successfully sent via SMTP to : '.$rec->email)->send();
                }
 */                try {
                    Notif::send($record->users, new NewMail($record,[],'1'));
                    Notification::make()->success()->title('Sent via SMTP')->send();
                } catch (Exception $exception) {
                    Notification::make()
                        ->title('We were not able to reach some recipients via SMTP')
                        ->danger()
                        ->send();
                }

            })->createAnother(false),
        ];
    }
    public function getHeading(): string
    {
        return 'Inbox';
    }
}
