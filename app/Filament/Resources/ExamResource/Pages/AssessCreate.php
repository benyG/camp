<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Jobs\SendEmail;
use App\Models\SMail;
use App\Models\Vague;
use App\Notifications\NewMail;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification as Notif;
use Illuminate\Support\Str;

class AssessCreate extends CreateRecord
{
    protected static string $resource = ExamResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reset')->label(__('form.res'))
                ->action(function (): void {
                    $this->form->fill();
                    PriceNotif::make()
                        ->title(__('form.e5'))
                        ->success()
                        ->send();

                    /*  Notification::make()
                         ->title(__('form.e5'))
                         ->success()
                         ->send(); */
                }),
        ];
    }

    protected function getFormActions(): array
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return [
            Actions\Action::make('re1')->label('Create')
                ->color('primary')
                ->modalDescription(function (): string {
                    $ix = cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    $dt = $this->data;
                    $msg = '';
                    if ($dt['type'] != '0') {
                        if (intval($dt['type']) > match (auth()->user()->ex) {
                            1 => $ix->maxts,0 => 40000000,
                            2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp,
                            5 => $ix->maxtv, 9 => $ix->maxtg,default => $ix->maxts
                        }) {
                            $msg = __('main.w43', ['rst' => lcfirst(__('form.tim'))]).' '.__('main.w42');
                        }
                    }
                    $mq = $dt['type'] == '1' ? match (auth()->user()->ex) {
                        1 => $ix->maxes,0 => 400000000,
                        2 => $ix->maxes, 3 => $ix->maxeu, 4 => $ix->maxep,
                        5 => $ix->maxev,9 => $ix->maxeg,default => $ix->maxes
                    }
                    : match (auth()->user()->ex) {
                        1 => $ix->maxes,0 => 4000000,
                        2 => $ix->maxs, 3 => $ix->maxu, 4 => $ix->maxp, 5 => $ix->maxv,9 => $ix->maxeg,default => $ix->maxes
                    };
                    if ($mq < intval($dt['quest'])) {
                        $msg = __('main.w43', ['rst' => 'questions']).' '.__('main.w42');
                    }

                    return $msg;
                })
                ->modalContent(fn (): View => view('components.pricing1', ['ix' => $ix]))
                ->action(function (): void {
                    if (auth()->user()->can('add-exam')) {
                        $this->validate();
                        $data = $this->form->getState();
                        $data['from'] = auth()->id();
                        $data['type'] = $data['type'] != '1' ? '0' : $data['type'];
                        $data['timer'] = $data['timer'] ?? '0';
                        $data['name'] = ($data['type'] != '1' ? 'Test' : 'Exam').'_'.Str::remove('-', now()->toDateString()).'_'.Str::random(5);

                        $record = \App\Models\Exam::create($data);

                        $datt = $data;
                        if (auth()->user()->ex != 0) {
                            $datt['user5'] = [auth()->id()];
                        } else {
                            if (empty($datt['classe'])) {
                                $datt['user5'] = $datt['user5'];
                            } else {
                                $vg = Vague::whereIn('id', $datt['classe'])->get();
                                foreach ($vg as $val) {
                                    $datt['user5'] = array_merge($datt['user5'], $val->users()->pluck('user')->toArray());
                                }
                                $datt['user5'] = array_unique($datt['user5']);
                            }
                        }
                        // dd($datt);
                        //  $record = $this->getRecord();
                        foreach ($datt['user5'] as $us) {
                            $record->users()->attach($us, ['added' => now()]);
                        }

                        // workaround for duplicates created by the repeater
                        foreach ($datt['examods'] as $es) {
                            $record->modules()->attach($es['module'], ['nb' => $es['nb']]);
                        }
                        Notification::make()->success()->title(__('form.e18'))->send();
                        if (auth()->user()->ex == 0) {
                            $ix = cache()->rememberForever('settings', function () {
                                return \App\Models\Info::findOrFail(1);
                            });
                            $ma = new SMail;
                            $ma->from = auth()->id();
                            $ma->sub = 'New Exam for you !';
                            $ma->content = 'Dear Bootcamper, <br>'.
                            'An exam was affected to you on the <b>'.$record->added_at.'<br>Title : '.$record->name.'<br>Certification : '.$record->certRel->name.'<br>Due Date : '.$record->due.'</b>'
                                .'<br><br> Please rush to the Bootcamp to take the exam !<br><br><i>The ITExamBootCamp Team</i>';
                            $ma->save();
                            foreach ($record->users as $us) {
                                $ma->users2()->attach($us->id);
                            }
                            Notification::make()->success()->title(__('form.e6'))->send();
                            if ($ix->smtp) {
                                foreach ($record->users as $us) {
                                    try {
                                        //   Notif::send($us, new NewMail($ma->sub,[now(),$record->name,$record->due,$record->certRel->name],'2'));
                                        $ma->users2()->updateExistingPivot($us->id, ['sent' => true, 'last_sent' => now()]);
                                        SendEmail::dispatch($us, $ma->sub, [now(), $record->name, $record->due, $record->certRel->name], '2');
                                        Notification::make()->success()->title(__('form.e8').$us->email)->send();
                                    } catch (Exception $exception) {
                                        Notification::make()
                                            ->title(__('form.e7').$us->email)
                                            ->danger()
                                            ->send();
                                    }
                                }
                            }
                        }
                        $txt = "Assessment created ! <br>
                Title: $record->title <br>
                Cert: ".$record->certRel->name.' <br>
                Type: '.($record->type == '1' ? 'Exam' : 'Test').' <br>
                Timer: '.($record->type == '1' ? $record->timer : 'N/A')." <br>
                Nb. Questions: $record->quest <br>
                Due date: $record->due <br>
                Users: ".implode(',', $record->users()->pluck('name')->toArray()).'<br>
                Module configuration: '.json_encode($datt['examods'][array_key_first($datt['examods'])]).' <br>
                ';
                        \App\Models\Journ::add(auth()->user(), 'Assessments', 1, $txt);
                        redirect()->to($this->getResource()::getUrl('index'));
                    }
                })
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalHeading(__('form.upp'))
                ->closeModalByClickingAway(false)
                ->modalHidden(function () {
                    $ix = cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    $dt = $this->data;
                    $bol = true;
                    if ($dt['type'] != '0') {
                        $bol = intval($dt['type']) <= match (auth()->user()->ex) {
                            1 => $ix->maxts,0 => 40000000,
                            2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp, 5 => $ix->maxtv,9 => $ix->maxtg,default => $ix->maxts
                        };
                    }
                    $mq = $dt['type'] == '1' ? match (auth()->user()->ex) {
                        1 => $ix->maxes,0 => 400000000,
                        2 => $ix->maxes, 3 => $ix->maxeu, 4 => $ix->maxep, 5 => $ix->maxev,9 => $ix->maxeg,default => $ix->maxes
                    }
                    : match (auth()->user()->ex) {
                        1 => $ix->maxes,0 => 4000000,
                        2 => $ix->maxs, 3 => $ix->maxu, 4 => $ix->maxp, 5 => $ix->maxv,9 => $ix->maxeg,default => $ix->maxes
                    };
                    $bol = $mq >= intval($dt['quest']);

                    if (auth()->user()->ex == 5) {
                        $bol = true;
                    }

                    return $bol && auth()->user()->can('add-exam');
                }),
            Actions\Action::make('cane')->label(__('form.can'))->color('gray')
                ->url(fn (): string => $this->getResource()::getUrl('index')),
        ];
    }
}
