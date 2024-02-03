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

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $modelLabel = 'assessment';
    protected static ?string $navigationLabel = 'Bootcamp';
    protected static ?string $slug = 'bootcamp';
    protected static ?int $navigationSort = 1;

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
                    $ix=cache()->rememberForever('settings', function () {
                        return \App\Models\Info::findOrFail(1);
                    });
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
                ->options([
                    '0' => 'Custom',
                    '1' => 'Typical',
                ])->live()->afterStateUpdated(function (?string $state, ?string $old,Get $get,Set $set) {
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
                ->rules(['min:'.$ix->mint,'max:'.match (auth()->user()->ex) {1 => $ix->maxts,0 => 40000000,
                 2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp, 5 => $ix->maxtv}]),
                Forms\Components\TextInput::make('quest')->numeric()->required()->label('Nb. Questions')
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
                    User::where('id','<>',auth()->id())->where('vague',$get('classe'))->get()->pluck('name', 'id'))->preload(),
                ]),
                Forms\Components\Section::make('')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->paginated([5,10,20,30,50,100, 'all'])
        ->query(auth()->user()->ex==0?Exam::where('from',auth()->id())->with('users')->with('modules')->latest('added_at'):Exam::has('users1')->with('users1')->with('modules')
        ->latest('added_at'))
        ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Title')
                ->description(fn (Exam $record): ?string => $record->descr),
                Tables\Columns\TextColumn::make('type')
                ->state(fn (Exam $record) => $record->type=='1'?(auth()->id()==$record->from?'Exam Simulation': 'Class Exam'):'Test your knowledge')
                ->badge()
                ->color(fn ($record): string =>$record->type=='1'?(auth()->id()==$record->from?'primary': 'danger'):'info')
            ->sortable(),
            Tables\Columns\TextColumn::make('quest')->label('Questions')->sortable(),
        Tables\Columns\TextColumn::make('users.name')->label('Users')
            ->hidden(auth()->user()->ex!=0),
        Tables\Columns\TextColumn::make('added')->label('Affected on')
                ->getStateUsing(fn (Exam $record) => $record->users1()->first()->pivot->added??null)
                    ->dateTime()->hidden(auth()->user()->ex==0)->since()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due')->label('Due Date')
                    ->dateTime()->since()->sortable()
                    ->tooltip(fn (Exam $record): ?string => $record->due),
                Tables\Columns\TextColumn::make('added_at')->label('Created on')
                    ->dateTime()->hidden(auth()->user()->ex!=0)
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_at')->label('Started on')
                ->getStateUsing(fn (Exam $record) => $record->users1()->first()->pivot->start_at??null)
                    ->dateTime()->hidden(auth()->user()->ex==0)->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_at')->label('Completed on')
                ->getStateUsing(fn (Exam $record) => $record->users1()->first()->pivot->comp_at??null)
                    ->dateTime()->hidden(auth()->user()->ex==0)->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('certi')->label('Certifications')
                ->relationship(name: 'certRel', titleAttribute: 'name',
                modifyQueryUsing: fn (Builder $query) =>auth()->user()->ex==0 ?$query :$query->has('users1')->where('pub',true))
                ->searchable()->label('Certifications')->multiple()
                ->preload(),
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
                        )
                        ;
                })

            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
              Tables\Actions\Action::make('sttr')->label('Start the Assessment')->icon('heroicon-o-play')->color('info')
              ->requiresConfirmation()
              ->modalIcon(fn(Exam $record):string=>$record->pub?'heroicon-o-eye-slash':'heroicon-m-play')
                    ->modalHeading('Start the Assessment')
                    ->modalDescription(fn(Exam $record):string=>'Are you sure you\'d like to start the assessement \''.$record->name.'\'? If it is an exam, you will be bound to complete it before closing the page.')
              ->action(function (Exam $record) {
               return redirect()->to(ExamResource::getUrl('assess', ['ex' => $record->name]));
              })
              ->visible(fn (Exam $record): bool =>$record->users1()->count()>0 && empty($record->users1()->first()->pivot->comp_at) && !empty($record->due) && now()<$record->due)
              ->iconButton(),
                Tables\Actions\Action::make('resend')->label('View the results')->iconButton()->icon('heroicon-o-document-check')
              ->modalCancelAction(fn (\Filament\Actions\StaticAction $action) => $action->label('Close'))
              ->modalSubmitAction(false)
              ->modalHeading(fn (Exam $record):string=> 'Results')
                /* ->modalContent(fn (Exam $record): View => view(
                    'filament.resources.exam-resource.pages.assess-res',
                    ['record' => $record],
                )) */
                ->visible(fn (Exam $record): bool =>$record->users1()->count()>0 &&!empty($record->users1()->first()->pivot->comp_at) || (!empty($record->due) && now()>$record->due))
                ->infolist([
                    Infolists\Components\Section::make('Assessment summary')->collapsible()->persistCollapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Title'),
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
                        Infolists\Components\TextEntry::make('modules.name')->label('Modules covered')
                        ->listWithLineBreaks()->columnSpan(2)->limitList(3)
                        ->expandableLimitedList()
                        ->bulleted()
                    ])
                    ->columns(3),
                    Infolists\Components\Section::make('Performance')->collapsible()->persistCollapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('scor')->label('Score'),
                        Infolists\Components\TextEntry::make('a1')->label('Correct Answers')
                        ->state(fn (Exam $record) => $record->type=='1'?(auth()->id()!=$record->from?'Exam Simulation': 'Class Exam'):'Test your knowledge')
                        ->badge(),
                        Infolists\Components\TextEntry::make('a2')->label('Completed in')
                        ->state(fn (Exam $record) => $record->type=='1'?$record->timer:'Unlimited'),
                        Infolists\Components\TextEntry::make('quest')->label('% Per Modules')
                        ->state(function (Exam $record): float {
                            return $record->amount * (1 + $record->vat_rate);
                        }),
                    ])
                    ->columns(),
                    Infolists\Components\Section::make('Details')->collapsible()->persistCollapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('ddder')->label('Your choices'),
                    ])
                    ->columns(),
                ])
                ->color('success'),
                Tables\Actions\DeleteAction::make()->iconButton()->visible(fn(Exam $record):bool=>empty($record->users1()->first()->pivot->start_at)),
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
