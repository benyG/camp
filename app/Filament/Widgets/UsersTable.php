<?php

namespace App\Filament\Widgets;

use App\Jobs\SendEmail;
use App\Models\CertConfig;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Module;
use App\Models\Question;
use App\Models\SMail;
use App\Models\User;
use App\Notifications\NewMail;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification as Notif;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Illuminate\Contracts\Pagination\CursorPaginator;

class UsersTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 50;
    public function table(Table $table): Table
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $uarr = User::where('ex', '<>', 0)->where('id', '<>', auth()->user()->id)->with('exams2')->get();
        $uarr2 = [];
        $earr = Exam::has('users')->with('users')->get();
        $eall = $earr->pluck('id');
        $rt = Question::with('answers')->get();
        foreach ($uarr as $us) {
            $uarr2[$us->id] = [0, 0, 0, 0, 0];

            $nt = $us->exams2->filter(function (Exam $value, int $key) {
                return !is_null($value->pivot->start_at);
            })->pluck('pivot.exam')->intersect($earr->where('type', '0')->pluck('id'))->count();
            $ne = $us->exams2->filter(function (Exam $value, int $key) {
                return !is_null($value->pivot->start_at);
            })->pluck('pivot.exam')->intersect($earr->where('type', '1')->pluck('id'))->count();
            $uarr2[$us->id][1] = $nt;
            $uarr2[$us->id][2] = $ne;
            $qt = 0;
            $pes = 0;
            $pga = 0;
            $uqt = [];
            foreach ($us->exams2 as $exa) {
                if (! empty($exa->pivot->gen) && in_array($exa->pivot->exam, $eall->toArray())) {
                    $res = $exa->pivot->gen;
                    $arrk = array_keys($res);
                    $qt += collect($arrk)->reduce(function (?int $carry, int|string $item) {
                        return $carry + (is_int($item) ? 1 : 0);
                    });
                    $ca = 0;
                    $rot = $rt->whereIn('id', $arrk);
                    foreach ($rot as $quest) {
                        $bm = $quest->answers->sum(function (\App\Models\Answer $aas) {
                            return $aas->qa->isok==1?1:0;
                        }) <= 1;
                        if ($bm) {
                            $ab = $quest->answers->where('id', $res[$quest->id][0])->sum(function (\App\Models\Answer $aas) {
                                return $aas->qa->isok==1?1:0;
                            });
                            if ($ab > 0) {
                                $ca++;
                                $pga++;
                            }
                        } else {
                            $ab2 = $quest->answers->whereIn('id', $res[$quest->id])->sum(function (\App\Models\Answer $aas) {
                                return $aas->qa->isok==0?1:0;
                            });
                            if ($ab2 == 0) {
                                $ca++;
                                $pga++;
                            }
                        }
                    }
                    if ($earr->where('type', '1')->where('id', $exa->pivot->exam)->count() > 0) {
                        $pes += (round(100 * $ca / $earr->where('type', '1')->where('id', $exa->pivot->exam)->first()->quest, 2) > $ix->wperc ? 1 : 0);
                    }
                }
            }
            $uarr2[$us->id][0] = $qt;
            $uarr2[$us->id][3] = round(100 * $pes / ($ne > 0 ? $ne : 1), 2);
            $uarr2[$us->id][4] = round(100 * $pga / ($qt > 0 ? $qt : 1), 2);
        }

        return $table->paginated([5, 10, 25, 50])->queryStringIdentifier('us1')->heading(__('main.w36'))
            ->query(
                User::with('vagues')->where('ex', '>', 1)->where('ex', '<', 6)->where('id', '<>', auth()->id())
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'indigo',
                        1 => 'Admin', 2 => 'Starter', 3 => 'User', 4 => 'Pro', 5 => 'VIP'
                    })
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'S. Admin',
                        1 => 'gray', 2 => 'info', 3 => 'success', 4 => 'danger', 5 => 'warning'
                    })
                    ->sortable()
                    ->description(fn (User $record): ?string => 'Total Q. :'.$uarr2[$record->id][0]),
                Tables\Columns\TextColumn::make('ix')->label('AI Calls'),
                Tables\Columns\TextColumn::make('a4')->label('Assessments')
                    ->state(fn (User $record) => $uarr2[$record->id][1])
                    ->formatStateUsing(fn ($state, $record): ?string => 'Exams :'.$uarr2[$record->id][2])
                    ->description(fn ($state): ?string => 'Tests :'.$state),
                Tables\Columns\TextColumn::make('a2')->label('Exam pass avg.')
                    ->state(fn (User $record) => $uarr2[$record->id][3])
                    ->formatStateUsing(fn ($state): ?string => $state.'%')
                    ->color(fn ($state): string => intval($state) >= $ix->wperc ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('a3')->label('% Good Ans.')
                    ->state(fn (User $record) => $uarr2[$record->id][4])
                    ->formatStateUsing(fn ($state): ?string => $state.'%')
                    ->color(fn ($state): string => intval($state) >= 50 ? (intval($state) >= 70 ? 'success' : 'warning') : 'danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('courses')->label('Certifications')->multiple()
                    ->relationship('courses', 'name',
                        fn (Builder $query) => $query->where('approve', true))->preload(),
                Tables\Filters\SelectFilter::make('vagues')->label('Class')->multiple()
                    ->relationship('vagues', 'name')->preload(),
                Tables\Filters\SelectFilter::make('ex')->label('Type')
                    ->options(['2' => 'Starter', '3' => 'User', '4' => 'Pro', '5' => 'VIP']),
            ])
            ->actions([
                Tables\Actions\Action::make('r1')->label(fn ($record) => 'Send message to \''.$record->name.'\'')->icon('heroicon-o-envelope')->iconButton()
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('sub')->label('Subject')
                                ->required()
                                ->maxLength(255),
                            TinyEditor::make('content')
                                ->required()
                                ->fileAttachmentsDisk('public')->fileAttachmentsVisibility('public')->fileAttachmentsDirectory('uploads')
                                ->columnSpanFull(),
                        ])->modalSubmitActionLabel('Send')
                        ->action(function (array $data, $record) {
                            $data['from'] = auth()->id();
                            $ma = Smail::create(['sub' => $data['sub'], 'content' => $data['content'], 'from' => auth()->id()]);
                            $ma->users()->attach($record->id);
                            Notification::make()->success()->title('Message sent')->send();
                            if (\App\Models\Info::first()->smtp) {
                                $para = [];
                                $opt = '1';
                                try {
                                    //    Notif::send($record, new NewMail($ma->sub,$para,$opt));
                                    SendEmail::dispatch($record, $ma->sub, $para, $opt);
                                    $ma->users2()->updateExistingPivot($record->id, ['sent' => true, 'last_sent' => now()]);
                                    Notification::make()->success()->title('Sent via SMTP to '.$record->email)->send();
                                } catch (Exception $exception) {
                                    Notification::make()
                                        ->title('We were not able to reach '.$record->email)
                                        ->danger()
                                        ->send();
                                }
                            }
                            $txt = "New message sent ! <br>
                        To: $record->name <br>
                        Subject: ".$data['sub'];
                            \App\Models\Journ::add(auth()->user(), 'A. Dashboard', 1, $txt);
                        }),
                Tables\Actions\Action::make('r2')->label('View Dashboard')->icon('heroicon-o-eye')->iconButton()
                        ->color('warning')->modalCancelAction(fn (\Filament\Actions\StaticAction $action) => $action->label('Close'))
                        ->modalSubmitAction(false)
                        ->modalContent(fn ($record): View => view(
                            'filament.pages.dash1',
                            ['record' => $record->id],
                        )),
                Tables\Actions\Action::make('r3')->label(fn ($record) => 'Send assessment')->icon('heroicon-o-clipboard-document-list')->iconButton()
                        ->color('danger')
                        ->modalSubmitActionLabel('Assign')
                        ->modalSubmitAction(fn (\Filament\Actions\StaticAction $action) => $action->color('primary'))
                        ->action(function ($record, $data) {
                            $exa = Exam::create(
                                ['name' => ($data['type'] != '1' ? 'Test' : 'Exam').'_'.Str::remove('-', now()->toDateString()).'_'.Str::random(5),
                                    'type' => $data['type'] != '1' ? '0' : $data['type'], 'from' => auth()->id(), 'certi' => $data['certi'],
                                    'due' => $data['due'], 'quest' => $data['quest'],
                                ]);
                            $exa->users()->attach($record->id, ['added' => now()]);
                            foreach ($data['examods'] as $es) {
                                $exa->modules()->attach($es['module'], ['nb' => $es['nb']]);
                            }
                            $ix = cache()->rememberForever('settings', function () {
                                return \App\Models\Info::findOrFail(1);
                            });
                            $ma = new SMail;
                            $ma->from = auth()->id();
                            $ma->sub = 'New Exam for you !';
                            $ma->content = 'Dear Bootcamper , <br>'.
                            'An exam was affected to you on the <b>'.$exa->added_at.'<br>Title : '.$exa->name.'<br>Certification : '.$exa->certRel->name.'<br>Due Date : '.$exa->due.'</b>'
                                .'<br><br> Please rush to the Bootcamp to take the exam !<br><br><i>The ITExamBootCamp Team</i>';
                            $ma->save();
                            $ma->users2()->attach($record->id);
                            Notification::make()->success()->title('User \''.$record->name.'\' was notified.')->send();
                            if ($ix->smtp) {
                                try {
                                    //  Notif::send($record, new NewMail($ma->sub,[now(),$exa->name,$exa->due,$exa->certRel->name],'2'));
                                    SendEmail::dispatch($record, $ma->sub, [now(), $exa->name, $exa->due, $exa->certRel->name], '2');
                                    $ma->users2()->updateExistingPivot($record->id, ['sent' => true, 'last_sent' => now()]);
                                    Notification::make()->success()->title('Successfully sent via SMTP to : '.$record->email)->send();
                                } catch (Exception $exception) {
                                    Notification::make()
                                        ->title('Error occured for '.$record->email)
                                        ->danger()
                                        ->send();
                                }
                            }
                            $txt = "Assessment created ! <br>
                    Title: $exa->title <br>
                    Cert: ".$exa->certRel->name.' <br>
                    Type: '.($exa->type == '1' ? 'Exam' : 'Test').' <br>
                    Timer: '.($exa->type == '1' ? $record->timer : 'N/A')." <br>
                    Nb. Questions: $exa->quest <br>
                    Due date: $exa->due <br>
                    Users: ".implode(',', $exa->users()->pluck('name')->toArray()).'<br>
                    Module configuration: '.json_encode($data['examods'][array_key_first($data['examods'])]).' <br>
                    ';
                            \App\Models\Journ::add(auth()->user(), 'A. Dashboard', 1, $txt);

                        })
                        ->form([
                            //hidden fields
                            Forms\Components\TextInput::make('from')
                                ->hidden(),
                            Forms\Components\TextInput::make('name')
                                ->hidden(),
                            //
                            Forms\Components\Section::make('General Settings')->columns(3)
                                ->schema([
                                    Forms\Components\Select::make('certi')->label('Certification')
                                        ->options(Course::where('pub', true)->get()->pluck('name', 'id'))
                                        ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                                            $ix = cache()->rememberForever('settings', function () {
                                                return \App\Models\Info::findOrFail(1);
                                            });
                                            if ($get('typee') == '1') {
                                                $cert = CertConfig::where('course', $state)->get();
                                                $set('examods', []);
                                                if ($cert->count() > 0) {
                                                    $cert = CertConfig::where('course', $state)->first();
                                                    $set('examods', $cert->mods);
                                                    $set('timer', $cert->timer);
                                                    $set('quest', $cert->questt);
                                                } else {
                                                    Notification::make()->warning()->title('Sorry, this certification doesn\'t have a typical configuration.')->send();
                                                }
                                            }
                                        })
                                        ->required()->live(),
                                    Forms\Components\Select::make('type')->label('Type')->selectablePlaceholder(false)->default('0')
                                        ->options([
                                            '0' => 'Test your knowlegde',
                                            '1' => 'Exam Simulation',
                                        ])->live(),
                                    Forms\Components\Select::make('typee')->label('Configuration')->selectablePlaceholder(false)->default('0')
                                        ->options(function (Get $get, Set $set) {
                                            if ($get('type') == '1') {
                                                return ['0' => 'Custom', '1' => 'Typical'];
                                            } else {
                                                $set('typee', '0');

                                                return ['0' => 'Custom'];
                                            }
                                        })
                                        ->live()->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                                            $ix = cache()->rememberForever('settings', function () {
                                                return \App\Models\Info::findOrFail(1);
                                            });
                                            if ($state == '1') {
                                                $cert = CertConfig::where('course', $get('certi'))->get();
                                                $set('examods', []);
                                                if ($cert->count() > 0) {
                                                    $cert = CertConfig::where('course', $get('certi'))->first();
                                                    $set('examods', $cert->mods);
                                                    $set('timer', $cert->timer);
                                                    $set('quest', $cert->quest);
                                                } else {
                                                    Notification::make()->warning()->title('Sorry, this certification doesn\'t have a typical configuration.')->send();
                                                }
                                            }
                                        }),
                                    Forms\Components\TextInput::make('timer')->numeric()->requiredIf('type', '1')->label('Timer (min)')
                                        ->readonly(fn (Get $get): bool => $get('typee') == '1')
                                        ->hidden(fn (Get $get): bool => $get('type') != '1')
                                        ->rules(['min:'.$ix->mint]),
                                    Forms\Components\TextInput::make('quest')->numeric()->required()->label('Nb. Questions')->readonly(fn (Get $get): bool => $get('typee') == '1')
                                        ->rules(['min:'.$ix->minq,
                                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                    $arrk = array_keys($get('examods'));
                                                    $rd = 0;
                                                    foreach ($arrk as $key) {
                                                        $rd += intval($get('examods.'.$key.'.nb'));
                                                    }
                                                    if ($rd != intval($value)) {

                                                        $fail('Max questions in modules should be '.$value.' . Actual :'.$rd);
                                                    }
                                                },
                                            ])->suffixAction(
                                                Action::make('Randomize')
                                                    ->icon('heroicon-m-arrows-pointing-out')->color('warning')
                                                    ->action(function (Set $set, $state, Get $get) {
                                                        // Notification::make()->success()->title($get('type'))->send();
                                                        $si = intval($state);
                                                        if ($si > 0) {
                                                            $arrk = array_keys($get('examods'));
                                                            if (count($arrk) > 0) {
                                                                if (count($arrk) == 1) {
                                                                    foreach ($arrk as $key) {
                                                                        $set('examods.'.$key.'.nb', $state);
                                                                    }
                                                                } else {
                                                                    $ii = 1;
                                                                    $ia = $si;
                                                                    foreach ($arrk as $key) {
                                                                        if ($ii == count($arrk)) {
                                                                            $set('examods.'.$key.'.nb', $ia);
                                                                            break;
                                                                        } else {
                                                                            $rd1 = rand(0, $ia);
                                                                            $set('examods.'.$key.'.nb', $rd1);
                                                                            $ia -= $rd1;
                                                                        }
                                                                        $ii++;
                                                                    }
                                                                }
                                                            } else {
                                                                Notification::make()->danger()->title('Please choose some modules')->send();
                                                            }
                                                        } else {
                                                            Notification::make()->danger()->title('Please specify the number of questions')->send();
                                                        }
                                                    })
                                            ),
                                    Forms\Components\DatePicker::make('due')->label('Due Date')
                                        ->required()->minDate(now()),
                                ]),
                            Forms\Components\Section::make('')->disabled(fn (Get $get): bool => $get('typee') == '1')
                                ->schema([
                                            Forms\Components\Repeater::make('examods')->grid(2)->label(function (Get $get) {
                                                $arrk = array_keys($get('examods'));
                                                $rd = 0;
                                                foreach ($arrk as $key) {
                                                    $rd += intval($get('examods.'.$key.'.nb'));
                                                }

                                                return 'Modules Configuration (Tt. Questions : '.$rd.')';
                                            })
                                                ->addActionLabel('Add a Module')->reorderable(false)->defaultItems(1)
                              //  ->relationship()
                                                ->schema([
                                                    Forms\Components\Select::make('module')->label('Name')
                                                 //   ->relationship('moduleRel2', 'name',modifyQueryUsing: fn (Builder $query,Get $get) => $query
                                                        ->options(fn (Get $get) => Module::where('course', $get('../../certi'))->pluck('name', 'id'))
                                                        ->required()->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                                    Forms\Components\TextInput::make('nb')->numeric()->required()->label('Questions')
                                                        ->rules(['numeric', fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                            //  dd($get('module'));
                                                            $rud = Question::where('module', $get('module'))->count();
                                                            if ($rud < intval($value)) {
                                                                $fail('Max questions for this module is '.$rud);
                                                            }
                                                        }])
                                                        ->default($ix->minq)->live(onBlur: true),
                                                ])->minItems(1)->maxItems(fn (Get $get): int => $get('type') == '1' ? Module::where('course', $get('certi'))->count() : 1),
                            ]),
                            Forms\Components\Section::make('Note')
                                ->description('Provide a small description of the assessment you are creating')
                                ->schema([
                                Forms\Components\Textarea::make('desc')->label('')->autosize(),
                            ]),
                        ]),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->ex == 0;
    }
    protected function paginateTableQuery(Builder $query): CursorPaginator
    {
        return $query->cursorPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
