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
use App\Models\Vague;
use App\Notifications\NewMail;
use App\Jobs\SendEmail;
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
        $data['from'] = auth()->id();
        $data['type'] =$data['type']!='1'? '0':$data['type'];
        $data['timer'] =$data['timer']??'0';
        $data['name'] = ($data['type']!='1'?'Test':'Exam').'_'.Str::remove('-',now()->toDateString()).'_'.Str::random(5);
        return $data;
    }
    protected function afterCreate(): void
    {
        $datt=$this->form->getState();
        if(auth()->user()->ex!=0){
            $datt['user5']=[auth()->id()];
        }
        else {
            if(empty($datt['classe']))
            $datt['user5']=$datt['user5'];
        else{
            $vg=Vague::whereIn('id',$datt['classe'])->get();
            foreach ($vg as $val) {
                $datt['user5']=  array_merge($datt['user5'],$val->users()->pluck('user')->toArray());
            }
            $datt['user5']=array_unique($datt['user5']);
        }
        }
        // dd($datt);
        $record=$this->getRecord();
        foreach($datt['user5'] as $us){
            $record->users()->attach($us,['added'=>now()]);
        }

        // workaround for duplicates created by the repeater
        foreach ($datt['examods'] as $es) {
            $record->modules()->attach($es['module'],['nb'=>$es['nb']]);
        }

        if(auth()->user()->ex==0){
            $ix=cache()->rememberForever('settings', function () {return \App\Models\Info::findOrFail(1);});
            $ma = new SMail;
            $ma->from=auth()->id();
            $ma->sub="New Exam for you !";
            $ma->content='Dear Bootcamper , <br>'.
            'An exam was affected to you on the <b>'.$record->added_at.'<br>Title : '.$record->name.'<br>Certification : '.$record->certRel->name.'<br>Due Date : '.$record->due.'</b>'
                .'<br><br> Please rush to the Bootcamp to take the exam !<br><br><i>The ITExamBootCamp Team</i>';
            $ma->save();
            foreach ($record->users as $us) {
                $ma->users2()->attach($us->id);
            }
            Notification::make()->success()->title('The users were notified.')->send();
            if($ix->smtp ){
                foreach ($record->users as $us) {
                    try {
                     //   Notif::send($us, new NewMail($ma->sub,[now(),$record->name,$record->due,$record->certRel->name],'2'));
                        $ma->users2()->updateExistingPivot($us->id, ['sent' => true,'last_sent' => now()]);
                        SendEmail::dispatch($us,$ma->sub,[now(),$record->name,$record->due,$record->certRel->name],'2');
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
        $txt="Assessment created ! <br>
        Title: $record->title <br>
        Cert: ".$record->certRel->name." <br>
        Type: ".($record->type=='1'?'Exam':'Test')." <br>
        Timer: ".($record->type=='1'?$record->timer:'N/A')." <br>
        Nb. Questions: $record->quest <br>
        Due date: $record->due <br>
        Users: ".implode(',',$record->users()->pluck('name')->toArray())."<br>
        Module configuration: ".json_encode($datt['examods'][array_key_first($datt['examods'])])." <br>
        ";
        \App\Models\Journ::add(auth()->user(),'Assessments',1,$txt);

    }

}
