<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\SMail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notif;
use Exception;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\NewMail;

class AssessCreate extends CreateRecord
{
    protected static string $resource = ExamResource::class;
    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reset')
                ->action(function (): void {
                    $this->form->fill();
                    Notification::make()
                        ->title('The form has been reset')
                        ->success()
                        ->send();
                }),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       // dd($data);
        $data['from'] = auth()->id();
        $data['name'] = ($data['type']==0?'Test':'Exam').'_'.Str::remove('-',now()->toDateString()).'_'.Str::random(5);
        if(auth()->user()->ex!=0){
            $data['due']=null;
            $data['user5']=[auth()->id()];
        }
        else{
            $data['user5']=empty($data['classe'])?$data['user5']:User::whereIn('vague',$data['classe'])->get()->pluck('id');
        }
        return $data;
    }
    protected function afterCreate(): void
    {
        $datt=$this->form->getState();
       // dd($datt);
        $record=$this->getRecord();
        foreach($datt['user5'] as $us){
            $record->users()->attach($us,['added'=>now()]);
        }
        if(auth()->user()->ex==0 && $record->type=='1'){
            $ix=cache()->rememberForever('settings', function () {return \App\Models\Info::findOrFail(1);});
            $ma = new SMail;
            $ma->sub="New Exam for you !";
            $ma->content='Dear Bootcamper , <br>'.
            'An exam was affected to you on the <b>'.$record->added_at.'<br>Title : '.$record->title.'<br>Due Date : '.$record->due.'</b>'
                .'<br><br> Please rush to the Bootcamp to take the exam !<br><br><i>The ITExamBootCamp Team</i>';
            $ma->save();
            foreach ($record->users as $us) {
                $ma->users2()->attach($us->id);
            }
            Notification::make()->success()->title('The users were notified.')->send();
            if($ix->smtp ){
                foreach ($record->users as $us) {
                    try {
                        Notif::send($us, new NewMail($ma->sub,[now(),$record->name,$record->due],'2'));
                        $ma->users2()->updateExistingPivot($us->id, ['sent' => true,'last_sent' => now()]);
                        Notification::make()->success()->title('Successfully sent via SMTP to : '.$us->email)->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Error occured for '.$us->email)
                            ->danger()
                            ->send();
                    }
                }
            }
        }
    }

}