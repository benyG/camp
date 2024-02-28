<?php

namespace App\Filament\Resources;

use Exception;
use App\Filament\Resources\ExamResource\Pages;
use App\Models\Exam;
use App\Models\CertConfig;
use App\Models\Module;
use App\Models\Question;
use App\Models\Course;
use App\Models\User;
use App\Models\Vague;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Component;
use Illuminate\Contracts\View\View;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Closure;
use Illuminate\Support\Carbon;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'assessment';
    protected static ?string $navigationLabel = 'Bootcamp';
    protected static ?string $slug = 'bootcamp';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        return $form
            ->schema([
                //hidden fields
                Forms\Components\TextInput::make('from')
                ->hidden(),
                Forms\Components\TextInput::make('name')
                ->hidden(),
                //
                Forms\Components\Section::make('General Settings')->columns(3)
                ->description(function(Get $get){
                    $ix=cache()->rememberForever('settings', function () { return \App\Models\Info::findOrFail(1);});
                   return $get('type')=='1'? 'Max timer is '.
                   match (auth()->user()->ex) {1 => $ix->maxts,0 => '(inf.)',
                    2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp, 5 => $ix->maxtv}.' min.'.
                    ' Max questions is '.match (auth()->user()->ex) {1 => $ix->maxes,0 => '(inf.)',
                        2 => $ix->maxes, 3 => $ix->maxeu, 4 => $ix->maxep, 5 => $ix->maxev}
                   :' Max Questions is '.match (auth()->user()->ex) {1 => $ix->maxs,0 => '(inf.)',
                    2 => $ix->maxs, 3 => $ix->maxu, 4 => $ix->maxp, 5 => $ix->maxv};
                })
                ->schema([
                Forms\Components\Select::make('certi')->label('Certification')
                ->relationship(name: 'certRel', titleAttribute: 'name',
                modifyQueryUsing: fn (Builder $query) =>auth()->user()->ex==0 ?$query :$query->has('users1')->where('pub',true))
               // ->options( Course::all()->pluck('name', 'id') : Course::->pluck('name', 'id'))
                ->afterStateUpdated(function (?string $state, ?string $old,Get $get,Set $set) {
                    $ix=cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    if($get('typee')=='1'){
                    $cert=CertConfig::where('course',$state)->get();
                    $set('examods',array());
                        if($cert->count()>0){
                            $cert=CertConfig::where('course',$state)->first();
                            $qe=match (auth()->user()->ex) {
                                0 => 400000000,
                                1 => $get('type')=='1'?$ix->maxes:$ix->maxs,
                                2 => $get('type')=='1'?$ix->maxes:$ix->maxs,
                                3 => $get('type')=='1'?$ix->maxeu:$ix->maxu,
                                4 => $get('type')=='1'?$ix->maxep:$ix->maxp,
                                5 => $get('type')=='1'?$ix->maxev:$ix->maxv,
                                default=>1,
                                };
                            $te=match (auth()->user()->ex) {
                                0 => 40000000,1 => $ix->maxts,2 => $ix->maxts,3 => $ix->maxtu,4 =>$ix->maxtp ,5 => $ix->maxtv,default=>1
                                };
                            $set('examods',$cert->mods);
                            $set('timer',$cert->timer>=$te?$te:$cert->timer);
                            $set('quest',$cert->quest>=$qe?$qe:$cert->quest);
                        }else Notification::make()->warning()->title('Sorry, this certification doesn\'t have a typical configuration.')->send();
                    }
                })
                ->required()->live(),
                Forms\Components\Select::make('type')->label('Type')->selectablePlaceholder(false)->default('0')
                ->options([
                    '0' => 'Test your knowlegde',
                    '1' => 'Exam Simulation',
                ])->live(),
                Forms\Components\Select::make('typee')->label('Configuration')->selectablePlaceholder(false)->default('0')
                ->options(function(Get $get, Set $set){
                     if($get('type')=='1')
                     return ['0' => 'Custom','1' => 'Typical'];
                    else {$set('typee','0');return ['0' => 'Custom'];}
                    })
                ->live()->afterStateUpdated(function (?string $state, ?string $old,Get $get,Set $set) {
                    $ix=cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    if($state=='1'){
                        $cert=CertConfig::where('course',$get('certi'))->get();
                        $set('examods',array());
                        if($cert->count()>0){
                            $cert=CertConfig::where('course',$get('certi'))->first();
                            $qe=match (auth()->user()->ex) {
                                0 => 400000000,
                                1 => $get('type')=='1'?$ix->maxes:$ix->maxs,
                                2 => $get('type')=='1'?$ix->maxes:$ix->maxs,
                                3 => $get('type')=='1'?$ix->maxeu:$ix->maxu,
                                4 => $get('type')=='1'?$ix->maxep:$ix->maxp,
                                5 => $get('type')=='1'?$ix->maxev:$ix->maxv,
                                default=>1,
                                };
                            $te=match (auth()->user()->ex) {
                                0 => 40000000,1 => $ix->maxts,2 => $ix->maxts,3 => $ix->maxtu,4 =>$ix->maxtp ,5 => $ix->maxtv,default=>1
                                };
                            $set('examods',$cert->mods);
                            $set('timer',$cert->timer>=$te?$te:$cert->timer);
                            $set('quest',$cert->quest>=$qe?$qe:$cert->quest);
                        }else Notification::make()->warning()->title('Sorry, this certification doesn\'t have a typical configuration.')->send();
                    }
                }),
                Forms\Components\TextInput::make('timer')->numeric()->requiredIf('type', '1')->label('Timer (min)')
                ->readonly(fn(Get $get):bool=>$get('typee')=='1')
                ->hidden(fn(Get $get):bool=>$get('type')!='1')
                ->rules(['min:'.$ix->mint,'max:'.match (auth()->user()->ex) {1 => $ix->maxts,0 => 40000000,
                 2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp, 5 => $ix->maxtv}]),
                Forms\Components\TextInput::make('quest')->numeric()->required()->label('Nb. Questions')->readonly(fn(Get $get):bool=>$get('typee')=='1')
                ->rules(['min:'.$ix->minq,fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                    $ix=cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
                    $mq=$get('type')=='1'? match (auth()->user()->ex) {1 => $ix->maxes,0 => 400000000,
                            2 => $ix->maxes, 3 => $ix->maxeu, 4 => $ix->maxep, 5 => $ix->maxev}
                            :match (auth()->user()->ex) {1 => $ix->maxes,0 => 4000000,
                            2 => $ix->maxs, 3 => $ix->maxu, 4 => $ix->maxp, 5 => $ix->maxv};
                    if ($mq <intval($value)) {
                        $fail("Max questions is ".$mq);
                    }
                },
                 fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $arrk=array_keys($get('examods'));
                        $rd=0;
                        foreach ($arrk as $key) {
                        $rd+=intval($get('examods.'.$key.'.nb'));
                        }
                    if ($rd!=intval($value)) {

                        $fail('Max questions in modules should be '.$value.' . Actual :'.$rd);
                    }
                }
                ])->suffixAction(
                    Action::make('Randomize')
                        ->icon('heroicon-m-arrows-pointing-out')->color('warning')
                        ->action(function (Set $set, $state,Get $get) {
                            // Notification::make()->success()->title($get('type'))->send();
                            $si=intval($state);
                            if($si>0){
                                $arrk=array_keys($get('examods'));
                                if(count($arrk)>0){
                                    if(count($arrk)==1)
                                        foreach ($arrk as $key) {$set('examods.'.$key.'.nb',$state);}
                                    else {
                                        $ii=1;$ia=$si;
                                        foreach ($arrk as $key) {
                                            if($ii==count($arrk)) {$set('examods.'.$key.'.nb',$ia);break;}
                                            else{
                                                $rd1=rand(0,$ia);
                                                $set('examods.'.$key.'.nb',$rd1);
                                                $ia-=$rd1;
                                            }
                                            $ii++;
                                        }
                                    }
                                }else Notification::make()->danger()->title('Please choose some modules')->send();
                            }else Notification::make()->danger()->title('Please specify the number of questions')->send();
                        })
                ),
                    Forms\Components\DatePicker::make('due')->label('Due Date')
                    ->required()->minDate(now())
            ]),
                Forms\Components\Section::make('Users')->columns(3)->hidden(auth()->user()->ex!=0)
                ->description('This part is for the S. Admin only. You may choose a class, or select individual users')
                ->schema([
                    Forms\Components\Select::make('classe')->label('Classes')->multiple()
                    ->options(Vague::all()->pluck('name', 'id'))->preload(),
                    Forms\Components\Select::make('user5')->label('Users')->multiple()
                    ->required(fn(Get $get):bool=>auth()->user()->ex==0 && $get('classe')==null)
                    ->options(fn(Get $get)=>$get('classe')==null?User::where('id','<>',auth()->id())->get()->pluck('name', 'id'):
                    User::where('id','<>',auth()->id())->whereRelation('vagues','clas',$get('classe'))->get()->pluck('name', 'id'))->preload(),
                ]),
                Forms\Components\Section::make('')->disabled(fn(Get $get):bool=>$get('typee')=='1')
                ->schema([
                    Forms\Components\Repeater::make('examods')->grid(2)->label(function(Get $get){
                        $arrk=array_keys($get('examods'));
                        $rd=0;
                        foreach ($arrk as $key) {
                        $rd+=intval($get('examods.'.$key.'.nb'));
                        }
                        return 'Modules Configuration (Tt. Questions : '.$rd.')';
                    })
                    ->addActionLabel('Add a Module')->reorderable(false)->defaultItems(1)
                  //  ->relationship()
                    ->schema([
                        Forms\Components\Select::make('module')->label('Name')
                     //   ->relationship('moduleRel2', 'name',modifyQueryUsing: fn (Builder $query,Get $get) => $query
                     ->options(fn(Get $get)=>Module::where('course',$get('../../certi'))->pluck('name', 'id'))
                        ->required()->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        Forms\Components\TextInput::make('nb')->numeric()->required()->label('Questions')
                        ->rules(['numeric',fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                          //  dd($get('module'));
                            $rud=Question::where('module',$get('module'))->count();
                        if ($rud<intval($value)) {
                            $fail('Max questions for this module is '.$rud);
                        }
                    }])
                        ->default($ix->minq)->live(onBlur: true),
                    ])->minItems(1)->maxItems(fn(Get $get):int=>$get('type')=='1'?Module::where('course',$get('certi'))->count():1)
                ]),
                Forms\Components\Section::make('Note')
                ->description('Provide a small description of the assessment you are creating')
                ->schema([
                    Forms\Components\Textarea::make('descr')->label('')->autosize()
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->paginated([5,10,20,30,50,100, 'all'])
        ->query(auth()->user()->ex==0?Exam::where('from',auth()->id())->with('users')->with('certRel')->with('modules')->latest('added_at'):Exam::has('users1')->with('certRel')->with('users1')->with('modules')
        ->latest('added_at'))
        ->columns([
            Tables\Columns\TextColumn::make('certRel.name')->sortable()->searchable()->label('Title')
            ->description(fn (Exam $record): ?string => $record->name),
            Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Code')->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(fn (Exam $record) => $record->type=='1'?(auth()->id()==$record->from?'Exam Simulation': 'Class Exam'):(Str::contains($record->name,'TestRX')?'Test based on failures':'Test your knowledge'))
                ->badge()
                ->color(fn ($record): string =>$record->type=='1'?(auth()->id()==$record->from?'primary': 'danger'):(Str::contains($record->name,'TestRX')?'warning':'info'))
            ->sortable(),
            Tables\Columns\TextColumn::make('quest')->label('Questions')->sortable(),
        Tables\Columns\TextColumn::make('users.name')->label('Users')->limit(30)->searchable()
        ->tooltip(fn($state):?string=>is_array($state)?implode(', ',$state):$state)
            ->hidden(auth()->user()->ex!=0),
        Tables\Columns\TextColumn::make('added')->label('Affected on')
                ->getStateUsing(fn (Exam $record) => $record->users1()->first()->pivot->added??null)
                ->tooltip(fn (Exam $record): ?string => $record->users1()->first()->pivot->added??null)
                    ->dateTime()->hidden(auth()->user()->ex==0)->since()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due')->label('Due Date')
                    ->dateTime()->since()->sortable()
                    ->tooltip(fn (Exam $record): ?string => $record->due),
                Tables\Columns\TextColumn::make('added_at')->label('Created on')
                ->tooltip(fn ($state) => $state)
                    ->dateTime()->hidden(auth()->user()->ex!=0)->since()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_at')->label('Started on')->since()
                ->tooltip(fn (Exam $record) => $record->users1()->first()->pivot->start_at??null)
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_at')->label('Completed on')->since()
                ->tooltip(fn (Exam $record) => $record->users1()->first()->pivot->comp_at??null)
                    ->dateTime()->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('certi')->label('Certifications')
                ->relationship(name: 'certRel', titleAttribute: 'name',
                modifyQueryUsing: fn (Builder $query) =>auth()->user()->ex==0 ?$query :$query->has('users1')->where('pub',true))
                ->label('Certifications')->multiple()->preload(),
                Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\Select::make('type')->label('Type')->selectablePlaceholder(false)->default('0')
                    ->options([
                        '0' => 'All',
                        '1' => 'Test your knowledge',
                        '2' => 'Exam Simulation',
                        '3' => 'Class Exam',
                    ])->live(),
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                    ->when(
                        empty($data['type']),
                        fn (Builder $query, $date): Builder => $query,
                    )
                    ->when(
                        $data['type']=='1',
                        fn (Builder $query, $date): Builder => $query->where('type', '0'),
                    )
                    ->when(
                        $data['type']=='2',
                        fn (Builder $query, $date): Builder => $query->where('type', '1')->where('from',  auth()->user()->ex!=0?'=':'<>', auth()->id()),
                    )
                    ->when(
                        $data['type']=='3',
                        fn (Builder $query, $date): Builder => $query->where('type', '1')->where('from', auth()->user()->ex==0?'=':'<>',auth()->id()),
                    );
                })
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
              Tables\Actions\Action::make('redddf')->label('Assessment summary')->iconButton()->icon('heroicon-o-eye')
              ->modalCancelAction(function (\Filament\Actions\StaticAction $action) {$action->color('primary');$action->label('Close');})
              ->modalSubmitAction(false)
            ->modalHeading(fn (Exam $record):string=> 'Assessment summary')
            ->visible(function (Exam $record){
                if(empty($record->users1()->first()->pivot->start_at))
                 return $record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due;
                 else
                     return $record->type==1? ($record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due) && $record->timer-now()->diffInMinutes($record->users1()->first()->pivot->start_at)>0:
                     ($record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due);
                 })
               ->infolist([
                  Infolists\Components\Section::make('Assessment summary')->collapsible()->persistCollapsed()
                  ->schema([
                    Infolists\Components\TextEntry::make('certRel.name')->label('Certification'),
                    Infolists\Components\TextEntry::make('name')->label('Assessment Title'),
                    Infolists\Components\TextEntry::make('timer')->label('Time')
                    ->state(fn (Exam $record) => $record->type=='1'?$record->timer:'Unlimited'),
                    Infolists\Components\TextEntry::make('quest')->label('Questions'),
                    Infolists\Components\TextEntry::make('due')->label('Due Date'),
                    Infolists\Components\TextEntry::make('added_at')->label('Created')->placeholder('N/A'),
                    Infolists\Components\TextEntry::make('modules.name')->label('Modules')->columnSpan(2)
                    ->listWithLineBreaks()->bulleted(),
                ])
                ->columns(3),
                    Infolists\Components\Section::make('Note')->compact()
                    ->schema([
                        Infolists\Components\TextEntry::make('descr')->label('')
                    ]),
                ])
              ->color('gray'),
              Tables\Actions\Action::make('sttr')->icon('heroicon-o-play')
              ->label(fn (Exam $record): string =>empty($record->users1()->first()->pivot->start_at)?'Start the Assessment':'Continue')
              ->color(fn (Exam $record): string =>empty($record->users1()->first()->pivot->start_at)?'info':'warning')
              ->requiresConfirmation()
              ->modalIcon(fn(Exam $record):string=>$record->pub?'heroicon-o-eye-slash':'heroicon-m-play')
                    ->modalHeading(fn($record)=>empty($record->users1()->first()->pivot->start_at)?'Start the Assessment':'Continue the Assessment')
                    ->modalDescription(fn(Exam $record):string=>empty($record->users1()->first()->pivot->start_at)?'Are you sure you\'d like to start the assessement \''.$record->name.'\'? If it is an exam, the countdown will start even if you logout or close the page. At the timer end. the assessment will output the result.':
                        'Are you sure you\'d like to continue the assessement \''.$record->name.'\'?')
              ->action(function (Exam $record) {
               return redirect()->to(ExamResource::getUrl('assess', ['ex' => $record->name]));
              })
              ->visible(function (Exam $record){
               // $rr=(!empty($record->users1()->first()->pivot->start_at) && $record->timer-now()->diffInMinutes($record->users1()->first()->pivot->start_at)>0);
               // if($record->id==5) dd($rr);
               if(empty($record->users1()->first()->pivot->start_at))
                return $record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due;
                else
                    return $record->type==1? ($record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due) && $record->timer-now()->diffInMinutes($record->users1()->first()->pivot->start_at)>0:
                    ($record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due);
                })
              ->iconButton(),
                Tables\Actions\Action::make('resend')->label('View the results')->iconButton()->icon('heroicon-o-document-check')
                ->modalCancelAction(function (\Filament\Actions\StaticAction $action) {$action->color('primary');$action->label('Close');})
                ->modalSubmitAction(false)
                ->modalHeading(fn (Exam $record):string=> 'Results')
                ->visible(function (Exam $record){
                    if(empty($record->users1()->first()->pivot->start_at))
                     return $record->users1()->count()>0 && (!empty($record->users1()->first()->pivot->comp_at) || (!empty($record->due) && now()>$record->due));
                     else
                         return $record->type==1? ($record->users1()->count()>0 && (!empty($record->users1()->first()->pivot->comp_at) || (!empty($record->due) && now()>$record->due) || $record->timer-now()->diffInMinutes($record->users1()->first()->pivot->start_at)<=0)):
                         ($record->users1()->count()>0 && (!empty($record->users1()->first()->pivot->comp_at) || (!empty($record->due) && now()>$record->due)));
                     })
                    ->infolist([
                        Infolists\Components\Section::make('Assessment summary')->collapsible()->persistCollapsed()
                        ->schema([
                            Infolists\Components\TextEntry::make('certRel.name')->label('Cetification'),
                            Infolists\Components\TextEntry::make('name')->label('Assessment Title'),
                            Infolists\Components\TextEntry::make('type')->label('Type')
                            ->state(fn (Exam $record) => $record->type=='1'?(auth()->id()==$record->from?'Exam Simulation': 'Class Exam'):'Test your knowledge')
                            ->badge()
                            ->color(fn ($record): string =>$record->type=='1'?(auth()->id()==$record->from?'primary': 'danger'):'info'),
                            Infolists\Components\TextEntry::make('timer')->label('Time')
                            ->state(fn (Exam $record) => $record->type=='1'?$record->timer:'Unlimited'),
                            Infolists\Components\TextEntry::make('quest')->label('Questions'),
                            Infolists\Components\TextEntry::make('due')->label('Due Date'),
                            Infolists\Components\TextEntry::make('added_at')->label('Created')->placeholder('N/A'),
                            Infolists\Components\TextEntry::make('comp_at')->label('Completed on')->placeholder('N/A')
                            ->state(fn (Exam $record) => $record->users1()->first()->pivot->comp_at??null),
                            Infolists\Components\TextEntry::make('descr')->label('Note')->columnSpanFull(),
                        ])
                        ->columns(3),
                        Infolists\Components\Section::make('Performance')->collapsible()->persistCollapsed()
                        ->schema(function($record){
                            $mod=array();

                            $ix=cache()->rememberForever('settings', function () {
                                return \App\Models\Info::findOrFail(1);
                            });
                                foreach ($record->modules as $gg) $mod[$gg->id]=[$gg->name,$gg->pivot->nb,0];
                            // dd($mod);
                                    $ca=0;

                                if(!empty($record->users1()->first()->pivot->gen) && is_array($record->users1()->first()->pivot->gen)){
                                    $res=$record->users1()->first()->pivot->gen;
                                    $arrk=array_keys($res);
                                    $qrr=array();
                                    $rt=Question::whereIn('id',$arrk)->with('answers')->with('moduleRel')->get();
                                    foreach ($rt as $quest) {
                                        $bm=$quest->answers()->where('isok',true)->count()<=1;
                                        if($bm){
                                            $ab=$quest->answers()->where('isok',true)->where('answers.id',$res[$quest->id][0])->count();

                                            if($ab>0) {
                                                $ca++;
                                                if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                                            }else $qrr[]=$quest->id;
                                        }else{
                                            $ab2=$quest->answers()->where('isok',false)->whereIn('answers.id',$res[$quest->id])->count();
                                            if($ab2==0) {
                                                $ca++;
                                                if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                                            }else $qrr[]=$quest->id;
                                        }
                                    }
                                    $record->llo=$qrr;
                                }
                            $mode="<ul>";
                            foreach ($mod as $va) {
                                $mode.="<li>".$va[0]." (".round(100*$va[2]/$va[1],2)."%)</li>";
                            }
                            $mode.="</ul>";
                            return [
                            Infolists\Components\TextEntry::make('scor')->label('Score')
                            ->color(fn ($state): string =>intval($state)>=$ix->wperc?'primary': 'danger')
                            ->state(fn ($record):string=>round(100*$ca/$record->quest,2).'%')->badge(),
                            Infolists\Components\TextEntry::make('a1')->label('Correct Answers')
                            ->state(fn($record):string=>$ca.' / '.$record->quest),
                            Infolists\Components\TextEntry::make('a2')->label('Completed in')
                            ->state(fn (Exam $record) =>!empty($record->users1()->first()->pivot->start_at)
                            && !empty($record->users1()->first()->pivot->comp_at)?
                            \Illuminate\Support\Carbon::parse($record->users1()->first()->pivot->comp_at)->diffInMinutes($record->users1()->first()->pivot->start_at).' min'
                            :'N/A'),
                            Infolists\Components\Actions::make([
                                Infolists\Components\Actions\Action::make('opoi')->label('Generate a test based on the failed questions')
                                    ->color('primary')->icon('heroicon-o-cog-8-tooth')->link()
                                    ->disabled(fn($record):bool=>Course::where('id',$record->certi)->whereRelation('users1','approve',true)->count()<=0)
                                    ->action(function ($record) {
                                        if(isset($record->llo)){
                                            $ess=new Exam();
                                            $ess->name='TestRX_'.Str::remove('-',now()->toDateString()).'_'.Str::random(5);
                                            $ess->due=now()->addDays(5);$ess->from=auth()->id();$ess->certi=$record->certi;
                                            $ess->timer=0;$ess->quest=count($record->llo);
                                            $ess->descr='Generated from '.$record->name;
                                            $ess->save();
                                            $rt=Question::selectRaw('count(id) as mcount, module')->whereIn('id',$record->llo)->groupBy('module')->get();
                                            foreach ($rt as $mod) {
                                                $ess->modules()->attach($mod->module,['nb'=>$mod->mcount]);
                                            }
                                            $ess->users()->attach(auth()->id(),['added'=>now(),'quest'=>json_encode($record->llo)]);
                                            Notification::make()->success()->title("Assessment generated.")->send();
                                        }else
                                        Notification::make()->success()->title("That assessment was never started.")->send();
                                    })
                            ]),
                            Infolists\Components\TextEntry::make('sccr')->label('% Per Modules')
                            ->state(fn()=>$mode)->html(),
                        ];
                        })
                        ->columns(2),
                    ])
                ->color('success'),
                Tables\Actions\Action::make('redds')->label('View the results')->iconButton()->icon('heroicon-o-document-check')
              ->modalCancelAction(function (\Filament\Actions\StaticAction $action) {$action->color('primary');$action->label('Close');})
              ->modalSubmitAction(false)
              ->modalHeading(fn (Exam $record):string=> 'Results')
                /* ->modalContent(fn (Exam $record): View => view(
                    'filament.resources.exam-resource.pages.assess-res',
                    ['record' => $record],
                )) */
                ->visible(fn (Exam $record): bool =>auth()->user()->ex==0)
                ->infolist([
                    Infolists\Components\Section::make('Assessment summary')->collapsible()->persistCollapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('certRel.name')->label('Certification'),
                        Infolists\Components\TextEntry::make('name')->label('Assessment Title'),
                        Infolists\Components\TextEntry::make('timer')->label('Time')
                        ->state(fn (Exam $record) => $record->type=='1'?$record->timer:'Unlimited'),
                        Infolists\Components\TextEntry::make('quest')->label('Questions'),
                        Infolists\Components\TextEntry::make('due')->label('Due Date'),
                        Infolists\Components\TextEntry::make('added_at')->label('Created')->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('comp_at')->label('Completed on')->placeholder('N/A')
                        ->state(fn (Exam $record) => $record->users1()->first()->pivot->comp_at??null),
                        Infolists\Components\TextEntry::make('modules.name')->label('Modules')->columnSpan(2)
                        ->listWithLineBreaks()->bulleted()->limitList(3),
                        Infolists\Components\TextEntry::make('descr')->label('Note')->columnSpanFull()
                    ])
                    ->columns(3),
                    Infolists\Components\Section::make('Performance')->collapsible()->persistCollapsed()
                    ->schema(function($record){
                        $ix=cache()->rememberForever('settings', function () {
                            return \App\Models\Info::findOrFail(1);
                        });
                      //  $cu=0;$ca=0;
                        $mode="<table class='w-full text-sm border-collapse table-auto'><thead><tr><th class='p-4 pt-0 pb-3 pl-8 font-medium text-left text-gray-400 border-b dark:border-gray-600 dark:text-gray-200'>Users</th><th class='p-4 pt-0 pb-3 pl-8 font-medium text-left text-gray-400 border-b dark:border-gray-600 dark:text-gray-200'>Time</th><th class='p-4 pt-0 pb-3 pl-8 font-medium text-left text-gray-400 border-b dark:border-gray-600 dark:text-gray-200'>Score</th></tr></thead><tbody class=''>";
                        foreach ($record->users as $us) {
                            $mode.="<tr><td class='p-4 pl-8 text-gray-500 border-b border-gray-100 dark:border-gray-700 dark:text-gray-400'>".$us->name ."</td><td class='p-4 pl-8 text-gray-500 border-b border-gray-100 dark:border-gray-700 dark:text-gray-400'>".(!empty($us->pivot->start_at) && !empty($us->pivot->comp_at)?
                            \Illuminate\Support\Carbon::parse($us->pivot->comp_at)->diffInMinutes($us->pivot->start_at):
                            'N/A')."</td>";
                       // $mod=array();
                           // foreach ($record->modules as $gg) $mod[$gg->id]=[$gg->name,$gg->pivot->nb,0];
                           // dd($mod);
                                $ca=0;
                            if(!empty($us->pivot->gen) && is_array($us->pivot->gen)){
                                $res=$us->pivot->gen;
                                $arrk=array_keys($res);
                                $rt=Question::whereIn('id',$arrk)->with('answers')->with('moduleRel')->get();
                                foreach ($rt as $quest) {
                                    $bm=$quest->answers()->where('isok',true)->count()<=1;
                                    if($bm){
                                        $ab=$quest->answers()->where('isok',true)->where('answers.id',$res[$quest->id][0])->count();
                                        if($ab>0) {
                                            $ca++;
                                          //  if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                                        }
                                    }else{
                                        $ab2=$quest->answers()->where('isok',false)->whereIn('answers.id',$res[$quest->id])->count();
                                        if($ab2==0) {
                                            $ca++;
                                           // if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                                        }
                                    }
                                }
                            }
                        $mode.="<td class='p-4 pl-8 border-b border-gray-100 dark:border-gray-700 text-".(round(100*$ca/$record->quest,2)>$ix->wperc?'primary':'danger')."-600'>".round(100*$ca/$record->quest,2)."%</td>";
                        }
                     //   $cu+=(round(100*$ca/$record->quest,2)>$ix->wperc?1:0);
                        $mode.="</tr></tbody></table>";
                        /* $mode.=" | Score : ".round(100*$ca/$record->quest,2)."%)</span><br>";
                        if(!empty($us->pivot->start_at)){
                            $mode.="<ul>";
                            foreach ($mod as $va) {
                                $mode.="<li>".$va[0]." (".round(100*$va[2]/$va[1],2)."%)</li>";
                            }
                            $mode.="</ul>";
                        } */

                        return [
                        Infolists\Components\TextEntry::make('scfcr')->label('')
                        ->state(fn()=>$mode)->html()->columnSpanFull(),
                    ];
                    })
                    ->columns(),
                ])
                ->color('warning'),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
             /*    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ])
            ->deferLoading()->striped()->persistFiltersInSession()
            ->persistSearchInSession()->persistColumnSearchesInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExams::route('/'),
            'create' => Pages\AssessCreate::route('/create'),
            'assess' => Pages\AssessGen::route('/assess/{ex}'),
        ];
    }

    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
