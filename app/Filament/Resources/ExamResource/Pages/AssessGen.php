<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use App\Models\Exam;
use Filament\Actions\Action as Action1;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

#[Lazy]
class AssessGen extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $resource = ExamResource::class;

    public Exam $record;

    #[Locked]
    public $quest;

    #[Locked]
    public $bm2 = false;

    #[Locked]
    public $aa = [];

    #[Locked]
    public $gen = [];

    #[Locked]
    public $atype;

    #[Locked]
    public $btext;

    #[Locked]
    public $qtext;

    #[Locked]
    public $qtot;

    #[Locked]
    public $qcur = 0;

    #[Locked]
    public $qcur2 = 1;

    #[Locked]
    public $tim;

    #[Locked]
    public $carr;

    #[Locked]
    public $ix;

    #[Locked]
    public $bm1;

    #[Locked]
    public $ico;

    #[Locked]
    public $in1;

    #[Locked]
    public $in2;

    #[Locked]
    public $cans;

    #[Locked]
    public $score = 0;

    #[Locked]
    public $qid;

    #[Locked]
    public $aid;

    #[Locked]
    public $qtx;

    #[Locked]
    public $iatext;

    #[Locked]
    public $iati = false;

    #[Locked]
    public $iatext2;

    #[Locked]
    public $iatext3;

    #[Locked]
    public $iati2 = false;

    #[Locked]
    public $iati3 = false;

    #[Validate('required', onUpdate: false)]
    public $ans;

    #[Validate('required', onUpdate: false)]
    public $ans2 = [];

    protected static string $view = 'filament.resources.exam-resource.pages.assess-gen';

    public function mount($ex): void
    {
        $this->record = Exam::has('users1')->where('name', $ex)->with('modules')->with('certRel')->with('users1')->firstOrFail();
        if (empty($this->record->users1()->first()->pivot->start_at)) {
            $this->record->users1()->updateExistingPivot(auth()->id(), [
                'start_at' => now()]);
        }
        if ($this->record->type == '1') {
            if (! empty($this->record->due) && now() > $this->record->due) {
                redirect()->to(ExamResource::getUrl());
            } elseif (now()->diffInMinutes($this->record->users1()->first()->pivot->start_at) >
            $this->record->timer) {
                redirect()->to(ExamResource::getUrl());
            }
        }
        //  cache()->forget('carr_'.$this->record->id.'_'.auth()->id());
        // $this->record->users1()->first()->pivot->start_at=now();
        $this->ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        $this->carr = cache()->get('carr_'.$this->record->id.'_'.auth()->id());
        if (empty($this->carr)) {
            $this->carr = cache()->remember('carr_'.$this->record->id.'_'.auth()->id(), 87000, function () {
                $qt = [];
                if (empty($this->record->users1()->first()->pivot->quest)) {
                    foreach ($this->record->modules as $md) {
                        $qt = array_merge($qt, $md->questions()->pluck('id')->random($md->pivot->nb)->toArray());
                    }
                } else {
                    $qt = json_decode($this->record->users1()->first()->pivot->quest);
                }
                $rt = \App\Models\Question::whereIn('id', $qt)->with('answers')->get();
                $at = $rt->pluck('questions.text', 'questions.id');

                return [0, 0, $this->record->timer, $this->record->name, $rt, $at];
            });
        }
        $this->tim = $this->record->timer - now()->diffInMinutes($this->record->users1()->first()->pivot->start_at);
        $this->qcur = $this->carr[0];
        $this->score = $this->carr[1];
        $this->quest = $this->carr[4];
        $this->gen = $this->carr[5];
        $this->qtot = count($this->quest);

        //$this->qcur=0;
        if ($this->qcur <= $this->qtot - 1) {
            $this->bm1 = $this->quest[$this->qcur]->answers->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 1 ? 1 : 0;
            }) <= 1;
            $this->aa = $this->quest[$this->qcur]->answers->random($this->quest[$this->qcur]->answers->count())->pluck('text', 'id');
            $this->qtext = $this->quest[$this->qcur]->text;
        } else {
            $sc = round(100 * $this->score / $this->qtot, 2);
            $this->btext = "
                <div>
                    <div class='text-sm text-center'>".trans_choice('main.as9', $this->score, ['sc' => $this->score])." $this->qtot</div> <br>
                    <div class='text-3xl text-center pb-9'>$sc % </div>
                    <div class='text-center ' style='--c-50:var(--".($sc >= $this->ix->wperc ? 'success' : 'danger').'-50);--c-400:var(--'.($sc >= $this->ix->wperc ? 'success' : 'danger').'-400);--c-600:var(--'.($sc >= $this->ix->wperc ? 'success' : 'danger')."-600);' >
                    <br><span
                    class='rounded-md text-lg font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30'
                    >".($sc >= $this->ix->wperc ? Str::ucfirst(__('form.pas')) : Str::ucfirst(trans_choice('form.fail', 1))).'</span>
                   </div>
                </div> <br> <br>
                ';
        }
        $this->ico = $this->qcur < $this->qtot ? 'heroicon-m-play' : '';
        $this->qcur2 = $this->qcur;
    }

    public function revAction(): Action1
    {
        return Action1::make('rev')->label(__('main.here'))->link()
            ->requiresConfirmation()->color('primary')
            ->modalIcon('heroicon-o-question-mark-circle')
            ->modalHeading(__('main.as10'))
            ->modalDescription(__('main.as11'))
            ->visible(auth()->user()->ex < 6)
            ->action(function () {
                $rev = new \App\Models\Review;
                $rev->user = auth()->id();
                $rev->quest = $this->qid;
                $rev->ans = json_encode($this->aid);
                $rev->save();
                Notification::make()->success()->title(__('main.as12'))->send();
            });
    }

    public function invAction(): Action1
    {
        return Action1::make('inv')->label(__('main.i3'))->size(ActionSize::Small)
            ->link()->disabled(function (): bool {
                if (empty($this->iatext2)) {
                    if (empty($this->qtext)) {
                        return true;
                    } else {
                        return ! empty($this->iatext);
                    }
                } else {
                    return true;
                }
            })
            ->color(function () {
                if (empty($this->iatext2)) {
                    if (empty($this->qtext)) {
                        return 'gray';
                    } else {
                        return empty($this->iatext) ? 'primary' : 'gray';
                    }
                } else {
                    return 'gray';
                }
            })
            ->icon('heroicon-o-question-mark-circle')
            ->closeModalByClickingAway(false)
            ->modalWidth(auth()->user()->can('call-ai')?\Filament\Support\Enums\MaxWidth::Small:\Filament\Support\Enums\MaxWidth::ExtraLarge)
            ->modalSubmitActionLabel(__('form.yes'))
            ->modalDescription(fn (): string =>auth()->user()->can('call-ai')?"":__('form.e33'))
            ->modalSubmitAction(auth()->user()->can('call-ai')?null:false)
            ->modalCancelAction(auth()->user()->can('call-ai')?null:false)
            ->modalContent(fn (): View => view(auth()->user()->can('call-ai')?'filament.pages.actions.iamod':'components.pricing2',
            auth()->user()->can('call-ai')? ['txt' => __('main.i4'), 'ex' => auth()->user()->ex]:['ix'=>cache()->rememberForever('settings', function () {return \App\Models\Info::findOrFail(1);})]))
            ->action(function () {
                if (auth()->user()->can('call-ai')) {
                    $ix = cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    $ik = 1;
                    $aitx = '';
                    foreach ($this->aa as $value) {
                        if (! is_string($value)) {
                            $value = $value->text;
                        }
                        $aitx .= $ik.'. '.$value."\n ";
                        $ik++;
                    }
                    $stats = $this->record->certRel->name." certification exam:
                    - Question :
                    $this->qtext
                    - Answers choices :".$aitx.'.';
                    try {
                        $apk = Crypt::decryptString($ix->apk);
                     //   dd( $apk);
                        $response = Http::withToken($apk)->post($ix->endp, [
                            'model' => $ix->model,
                            'messages' => [
                                ['role' => 'system', 'content' => $ix->cont1],
                                ['role' => 'user', 'content' => $stats.__('main.i5')],
                            ],
                        ])
                            ->json();
                      //   dd($response);
                        if (is_array($response['choices'])) {
                            $this->iatext3 = __('main.i6', ['name' => auth()->user()->name]);
                            $this->iati = true;
                            if (! $this->iati3) {
                                $this->iati3 = true;
                            }
                            $this->iatext = $response['choices'][0]['message']['content'];
                            iac_decr();
                        } else {
                            Notification::make()->danger()->title(__('form.e10'))->send();
                        }
                    } catch (DecryptException $e) {
                        Notification::make()->danger()->title(__('form.e11'))->send();
                    } catch (ConnectionException $e) {
                        Notification::make()->danger()->title(__('form.e12'))->send();
                    }
                }
            });

    }

    public function inaAction(): Action1
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

        return Action1::make('ina')->label(__('main.i8'))->size(ActionSize::Small)->modalSubmitActionLabel('Yes')
            ->icon('heroicon-o-light-bulb')->disabled(function (): bool {
                if (empty($this->cans)) {
                    return true;
                } else {
                    return ! empty($this->iatext2);
                }
            })
            ->link()->color(function () {
                if (empty($this->cans)) {
                    return 'gray';
                } else {
                    return empty($this->iatext2) ? 'primary' : 'gray';
                }
            })
            ->closeModalByClickingAway(false)
            ->modalWidth(auth()->user()->can('call-ai')?\Filament\Support\Enums\MaxWidth::Small:\Filament\Support\Enums\MaxWidth::ExtraLarge)
            ->modalContent(fn (): View => view(auth()->user()->can('call-ai')?'filament.pages.actions.iamod':'components.pricing2',
            auth()->user()->can('call-ai')? ['txt' => __('main.i7'), 'ex' => auth()->user()->ex]:['ix'=>cache()->rememberForever('settings', function () {return \App\Models\Info::findOrFail(1);})]))
            ->modalSubmitAction(auth()->user()->can('call-ai')?null:false)
            ->modalCancelAction(auth()->user()->can('call-ai')?null:false)
            ->modalDescription(fn (): string =>auth()->user()->can('call-ai')?"":__('form.e33'))
            ->action(function () {
                if (auth()->user()->can('call-ai')) {
                    $ix = cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    $ik = 1;
                    $aitx = '';
                    foreach ($this->aa as $value) {
                        if (! is_string($value)) {
                            $value = $value->text;
                        }
                        $aitx .= $ik.'. '.$value."\n ";
                        $ik++;
                    }
                    $stats = $this->record->certRel->name." certification exam:
                 - Question :
                 $this->qtext
                 - Answers choice :".$aitx.'.';
                    try {
                        $apk = Crypt::decryptString($ix->apk);
                        //  dd($apk);
                        $response = Http::withToken($apk)->post($ix->endp, [
                            'model' => $ix->model,
                            'messages' => [
                                ['role' => 'system', 'content' => $ix->cont2],
                                ['role' => 'user', 'content' => $stats.__('main.i5')],
                            ],
                        ])
                            ->json();
                        // dd($response["choices"][0]["message"]["content"]);
                        if (is_array($response['choices'])) {
                            $this->iatext3 = __('main.i6', ['name' => auth()->user()->name]);
                            $this->iati2 = true;
                            if (! $this->iati3) {
                                $this->iati3 = true;
                            }
                         // $this->iatext2 = preg_replace('/(\d+\. \*\*|- \*\*|- )/', '<br>$1',$response['choices'][0]['message']['content'] );
                            $this->iatext2 = $response['choices'][0]['message']['content'];
                            iac_decr();
                        } else {
                            Notification::make()->danger()->title(__('form.e10'))->send();
                        }
                    } catch (DecryptException $e) {
                        Notification::make()->danger()->title(__('form.e11'))->send();
                    } catch (ConnectionException $e) {
                        Notification::make()->danger()->title(__('form.e12'))->send();
                    }
                }
            });
    }

    public function validateData()
    {
        // dd($this->qcur);
        if ($this->bm1) {
            $this->validate([
                'ans' => 'required',
            ]);
        } else {
            $this->validate([
                'ans2' => 'required',
            ]);
        }
    }

    public function populate()
    {
        $this->qcur2++;
        $this->iati3 = false;
        $this->iatext3 = $this->iatext = $this->iatext2 = '';
        $this->iati1 = $this->iati = false;
        $this->ico = $this->qcur < $this->qtot ? 'heroicon-m-play' : '';
        if ($this->qcur <= $this->qtot - 1) {
            $this->aa = $this->quest[$this->qcur]->answers->random($this->quest[$this->qcur]->answers->count())->pluck('text', 'id');
            $this->bm1 = $this->quest[$this->qcur]->answers->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 1 ? 1 : 0;
            }) <= 1;
            $this->qtext = $this->quest[$this->qcur]->text;
            $this->ans = null;
            $this->ans2 = [];
            $this->cans = null;
            $this->bm2 = false;
        } else {
            $this->carr[0] = $this->qcur;
            $this->carr[1] = $this->score;
            cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
            $this->record->users1()->updateExistingPivot(auth()->id(), [
                'comp_at' => now()]);
            $this->aa = [];
            $this->qtext = null;
            $this->ans = null;
            $this->ans2 = [];
            $this->cans = null;
            $this->bm2 = false;
            $sc = round(100 * $this->score / $this->qtot, 2);
            $this->btext = "
                    <div class=''>
                        <div class='text-sm text-center'>".trans_choice('main.as9', $this->score, ['sc' => $this->score])." $this->qtot</div> <br>
                        <div class='text-3xl text-center pb-9'>$sc % </div>
                        <div class='text-center ' style='--c-50:var(--".($sc >= $this->ix->wperc ? 'success' : 'danger').'-50);--c-400:var(--'.($sc >= $this->ix->wperc ? 'success' : 'danger').'-400);--c-600:var(--'.($sc >= $this->ix->wperc ? 'success' : 'danger')."-600);' >
                        <br><span
                        class='rounded-md text-lg font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30'
                        >".($sc >= $this->ix->wperc ? Str::ucfirst(__('form.pas')) : Str::ucfirst(trans_choice('form.fail', 1))).'</span>
                    </div>
                    </div> <br> <br>
                    ';
            $tkxt = 'Completed Assessment '.$this->record->title.' ('.$this->record->certRel->name.') ';
            \App\Models\Journ::add(auth()->user(), 'Assessments', 2, $tkxt);
            // dd($this->qcur2.'-'.$this->qtot);
            if ($this->qcur2 > $this->qtot) {
                return redirect()->to(ExamResource::getUrl());
            }
            $this->qcur2++;
            //
        }

    }

    public function incrQuest()
    {
        if ($this->bm1) {
            $ab = $this->quest[$this->qcur]->answers->where('id', $this->ans)->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 1 ? 1 : 0;
            });
            $au = '';
            if ($this->quest[$this->qcur]->answers->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 1 ? 1 : 0;
            }) > 0) {
                $au = $this->quest[$this->qcur]->answers->filter(function (\App\Models\Answer $aas, int $key) {
                    return $aas->qa->isok == 1;
                })->first()->text;
            }

            $this->gen[$this->quest[$this->qcur]->id] = [$this->ans];
            $this->aid = [$this->ans];
            $this->qid = $this->quest[$this->qcur]->id;
            if ($ab > 0) {
                $this->score++;
            }
            if ($this->record->type == '0' && $this->bm2 == false) {
                // DO NOT REMOVE ALT='', CODE NEEDED FOR REVIEW BUTTON
                $this->cans = $ab > 0 ? "
                <span class='text-sm text-primary-600'> <br>
        ".__('main.as13').'</span>' :
                "<span alt='' style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-sm text-custom-600'>
                <br>  ".__('main.as14')." <br> </span><span class='text-xs'>".__('main.as13').": <br>
                $au</span>";
            }
        } else {
            $ab2 = $this->quest[$this->qcur]->answers->whereIn('id', $this->ans2)->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 0 ? 1 : 0;
            });
            $au2 = '';
            if ($this->quest[$this->qcur]->answers->sum(function (\App\Models\Answer $aas) {
                return $aas->qa->isok == 1 ? 1 : 0;
            }) > 0) {
                $au2 = $this->quest[$this->qcur]->answers->filter(function (\App\Models\Answer $aas, int $key) {
                    return $aas->qa->isok == 1;
                })->pluck('text');
            }
            $this->gen[$this->quest[$this->qcur]->id] = $this->ans2;
            $this->aid = $this->ans2;
            $this->qid = $this->quest[$this->qcur]->id;
            if ($this->record->type == '0' && $this->bm2 == false) {
                if ($ab2 == 0) {
                    $this->score++;
                }
            }
            // DO NOT REMOVE ALT='', CODE NEEDED FOR REVIEW BUTTON
            $this->cans = $ab2 == 0 ? "
                <span class='text-sm text-primary-600'> <br>
                ".__('main.as15').'</span>' :
            "<span alt='' style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-sm text-custom-600'>
                <br>  ".__('main.as17')."<br> </span><span class='text-xs'>".__('main.as16').' : <br>
                '.$au2->join('<br>').'</span>';
        }
        $this->bm2 = true;
        $this->qcur++;
        $this->carr[0] = $this->qcur;
        $this->carr[1] = $this->score;
        $this->carr[5] = $this->gen;
        cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
        $this->record->users1()->updateExistingPivot(auth()->id(), [
            'gen' => $this->gen]);

    }

    public function register($opt = false)
    {
        $this->resetErrorBag();
        if ($this->qcur2 > $this->qtot) {
            return redirect()->to(ExamResource::getUrl());
        }
        if ($opt == false && $this->qcur <= $this->qtot - 1) {
            $this->validateData();
        }
        if ($opt || ($this->record->type == '1' && $this->record->timer - now()->diffInMinutes($this->record->users1()->first()->pivot->start_at) <= 0)) {
            $this->qcur = $this->qtot; //dd($opt);
            $this->qcur2 = $this->qtot - 1; //dd($opt);
        }
        if ($this->qcur >= $this->qtot) {
            $this->populate();
        } else {
            if ($this->record->type == '1') {
                $this->incrQuest();
                $this->populate();
            } else {
                if ($this->bm2) {
                    $this->populate();
                } else {
                    $this->incrQuest();
                }
            }
        }
    }

    public function closeComp()
    {
        $this->register(true);
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->type == 0 ? __('form.tyk') : ($this->record->from != auth()->id() ? __('form.cex') : __('form.exas'));
    }

    public function getSubheading(): string|Htmlable
    {
        return 'Certification'.__('main.space').': '.$this->record->certRel->name.' | '.__('form.pas1').__('main.space').': '.$this->ix->wperc.($this->record->type == '0' ? '' : ' | '.__('form.tim').__('main.space').': '.$this->record->timer.' min');
    }

    public function messages()
    {
        return [
            'ans.required' => __('form.e9'),
            'ans2.email' => __('form.e9'),
        ];
    }
}
