<?php

namespace App\Filament\Resources\SmailResource\Pages;

use App\Filament\Resources\SmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notif;
use App\Models\User;
use Exception;
use App\Notifications\NewMail;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
class ManageSmails extends ManageRecords
{
    protected static string $resource = SmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->modalHeading(__('main.sm1'))->modalSubmitActionLabel(__('form.send'))
            ->label(__('form.nms'))
            ->mutateFormDataUsing(function (array $data): array {
                $data['from'] = auth()->id();
                return $data;
            })->after(function(Model $record){
                if(auth()->user()->ex>=2){
                    $record->users()->attach(User::where('ex',0)->first()->id);
                }
                if(\App\Models\Info::first()->smtp){
                    $para=array(); $opt='1';
                    if(auth()->user()->ex>=2){
                        $para=[auth()->user()->name,auth()->user()->email];
                        $opt='4';
                    }
                    foreach ($record->users2 as $us) {
                        try {
                         //   Notif::send($us, new NewMail($record->sub,$para,$opt));
                         \App\Jobs\SendEmail::dispatch($us,$record->sub,$para,$opt);
                            $record->users2()->updateExistingPivot($us->id, ['sent' => true,'last_sent' => now()]);
                            Notification::make()->success()->title(__('form.e8').' '.$us->email)->send();
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(__('form.e7').$us->email)
                                ->danger()
                                ->send();
                        }
                    }
                    $txt="New message sent ! <br>
                    To: ".implode(',',$record->users2()->pluck('name')->toArray())." <br>
                    Subject: ".$record->sub;
                    \App\Models\Journ::add(auth()->user(),'Inbox',1,$txt);
            }

            })->createAnother(false),
        ];
    }
    public function getHeading(): string
    {
        return __('main.m13');
    }
    public function getTabs(): array
    {
        return [
            'inbox' => Tab::make()->label(__('form.ib'))
                ->modifyQueryUsing(fn (Builder $query) => $query
                ->has('users1')->with('users1')->latest()
                        ),
            'outbox' => Tab::make()->label(__('form.ob'))
                ->modifyQueryUsing(fn (Builder $query) => $query
                ->where(function (Builder $query) {
                    $query->where('from',auth()->user()->id)
                          ->where('hid',false);})->latest()
            ),
        ];
    }
    public function getDefaultActiveTab(): string | int | null
{
    return 'inbox';
}
}
