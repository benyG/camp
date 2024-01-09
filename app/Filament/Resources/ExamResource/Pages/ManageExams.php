<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\SMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\Imail;
use Filament\Notifications\Notification;

class ManageExams extends ManageRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('e')->label('Certifications')->color('info')
            ->url(fn (): string => $this->getResource()::getUrl('certif')),
            Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                $data['from'] = auth()->id();
                if(auth()->user()->ex!=0){
                    $data['type']=false;
                    $data['due']=null;
                    $data['users']=auth()->id();
                }
                return $data;
            })->after(function(Model $record){
                foreach ($record->users as $rec) {
                    $ma = new SMail;
                    $ma->sub="New Exam for you !";
                Mail::to($rec->email)->send(new Imail($ma,[$rec->name,$rec->email,now(),$record->name,$record->due],'2'));
                Notification::make()->success()->title('Successfully sent via SMTP to : '.$rec->email)->send();
                }
            })->createAnother(false),
        ];
    }
}
