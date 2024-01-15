<?php

namespace App\Filament\Resources;

use Exception;
use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use App\Models\CertConfig;
use App\Models\Module;
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
                ->options(Course::has('users1')->where('pub',true)->pluck('name', 'id'))
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
                Forms\Components\Select::make('type')->label('Type')->required()->selectablePlaceholder(false)
                ->options([
                    '0' => 'Test',
                    '1' => 'Exam',
                ])->live(),
                Forms\Components\Select::make('typee')->label('Configuration')->required()->selectablePlaceholder(false)
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
                Forms\Components\TextInput::make('timer')->numeric()->step(5)->requiredIf('type', '1')->label('Timer (min)')
                ->rules(['min:'.$ix->mint,'max:'.match (auth()->user()->ex) {1 => $ix->maxts,0 => 40000000,
                 2 => $ix->maxts, 3 => $ix->maxtu, 4 => $ix->maxtp, 5 => $ix->maxtv}]),
                Forms\Components\TextInput::make('quest')->numeric()->step(5)->required()->label('Nb. Questions')
                ->rules(['min:'.$ix->minq,fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
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
                    if ($rd >intval($value)) {

                        $fail('Max questions in modules should not exceed'.$value.' . Actual :'.$rd);
                    }
                }
                ])->suffixAction(
                    Action::make('Randomize')
                        ->icon('heroicon-m-arrows-pointing-out')
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
                    Forms\Components\DatePicker::make('due')->label('Due Date')
                    ->required(fn (Get $get): bool => $get('type')==1 && auth()->user()->ex==0)->minDate(now())
                ]),
                Forms\Components\Section::make('')
                ->schema([
                    Forms\Components\Repeater::make('examods')->grid(2)->label(function(){
                        $nd=0;
                        return 'Modules Configuration (Tt. Questions : '.$nd.')';
                    })
                    ->addActionLabel('Add a Module')->reorderable(false)->defaultItems(1)
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('module')->label('Name')
                        ->relationship('moduleRel', 'name',modifyQueryUsing: fn (Builder $query,Get $get) => $query
                        ->where('course',$get('../../certi')))
                        ->required()->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        Forms\Components\TextInput::make('nb')->numeric()->step(5)->required()->label('Questions')->rules(['numeric'])
                        ->default($ix->minq),
                    ])->minItems(1)->maxItems(fn(Get $get):int=>$get('type')=='1'?Module::where('course',$get('certi'))->count():1)
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(auth()->user()->ex==0?Exam::where('from',auth()->id())->with('users')->latest('added_at'):Exam::has('users1')->with('users1')
        ->latest('added_at'))
        ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Title')
                ->description(fn (Exam $record): ?string => $record->descr),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (Exam $record): string => $record->type== 0? 'Test' : 'Exam')
                    ->color(fn (string $state): string => match ($state) {'0' => 'warning','1' => 'success',})
                    ->sortable(),
                    Tables\Columns\TextColumn::make('users.name')->label('Users')
                    ->hidden(auth()->user()->ex!=0),
                Tables\Columns\TextColumn::make('added')->label('Affected on')
                    ->dateTime()->hidden(auth()->user()->ex==0)->since()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due')->label('Due Date')
                    ->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('added_at')->label('Created on')
                    ->dateTime()->hidden(auth()->user()->ex!=0)
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Start on')->label('Started on')
                    ->dateTime()->hidden(auth()->user()->ex==0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comp_at')->label('Completed on')
                    ->dateTime()->hidden(auth()->user()->ex==0)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('resend')->label('Results')
                ->action(function (Smail $record) {

                })->button()->color('success')->visible(fn (): bool =>auth()->user()->ex==0),
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['from'] = auth()->id();
                    if(auth()->user()->ex!=0) {
                        $data['type']=false;
                        $data['due']=null;
                        $data['users']=auth()->id();
                    }
                    return $data;
                }),
           //     Tables\Actions\DeleteAction::make(),
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
        ];
    }
    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }
}
