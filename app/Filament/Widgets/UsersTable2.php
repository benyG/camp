<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Exam;
use App\Models\Module;
use App\Models\Question;
use App\Models\Course;
use App\Models\User;
use App\Models\Vague;
use App\Models\CertConfig;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification as Notif;
use App\Notifications\NewMail;
use Filament\Notifications\Notification;
use App\Models\SMail;
use Filament\Forms;
use Filament\Forms\Form;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Component;
use Illuminate\Contracts\View\View;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
class UsersTable2 extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Performance summary';
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 10;
    public $record;
    public function mount($usrec=null)
    {
       // dd($usrec);
        $this->record=User::find($usrec)??auth()->user();
    }
    public static function canView(): bool
    {
       // return true;
       return auth()->user()->ex>=2;
    }
    public function table(Table $table): Table
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });

      $this->record->loadMissing('exams2');

      $us=$this->record;
        $earr=Exam::has('users')->with('users')->get();
        $eall=$earr->pluck('id');
        $rt=Question::with('answers')->get();
         $uarr2=array();
            foreach ($us->exams2 as $exa) {
                if(in_array($exa->pivot->exam,$eall->toArray())){
                    if(!empty($exa->pivot->gen)){
                        $res=$exa->pivot->gen;
                        $arrk=array_keys($res);
                        $ca=0;
                        $rot=$rt->whereIn('id',$arrk);
                        foreach ($rot as $quest) {
                            $bm=$quest->answers()->where('isok',true)->count()<=1;
                            if($bm){
                                $ab=$quest->answers()->where('isok',true)->where('answers.id',$res[$quest->id][0])->count();
                                if($ab>0) {
                                    $ca++;
                                }
                            }else{
                                $ab2=$quest->answers()->where('isok',false)->whereIn('answers.id',$res[$quest->id])->count();
                                if($ab2==0) {
                                    $ca++;
                                }
                            }
                        }
                    // dd('dk');
                    $uarr2[$exa->pivot->exam]=round(100*$ca/$earr->where('id',$exa->pivot->exam)->first()->quest,2);
                    }else if(!empty($exa->pivot->start_at)) {$uarr2[$exa->pivot->exam]=0;}
                    else if(empty($exa->pivot->start_at) && now()>$exa->due) {$uarr2[$exa->pivot->exam]=-1;}
                }
            }
           // dd(Exam::with('certRel')->where('from',$this->record->id)->orWhereRelation('users', 'user', $this->record->id)->latest('added_at')->count());
        return $table->paginated([5,10,25,50])->queryStringIdentifier('us2')
        ->query(Exam::with('certRel')->where('from',$this->record->id)->orWhereRelation('users', 'user', $this->record->id)->latest('added_at'))
            ->columns([
                Tables\Columns\TextColumn::make('certRel.name')->sortable()->searchable()->label('Title')
                ->description(fn (Exam $record): string => $record->name),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->label('Code')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(fn (Exam $record) => $record->type=='1'?($this->record->id==$record->from?'Exam Simulation': 'Class Exam'):(Str::contains($record->name,'TestRX')?'Test based on failures':'Test your knowledge'))
                ->badge()
                ->color(fn ($record): string =>$record->type=='1'?($this->record->id==$record->from?'primary': 'danger'):(Str::contains($record->name,'TestRX')?'warning':'info'))
            ->sortable(),
                Tables\Columns\TextColumn::make('a4')->label('Result')->sortable()->badge()
                ->state(fn (Exam $record) => $uarr2[$record->id]??0)
                ->color(fn ($record): string =>isset($uarr2[$record->id])? ($uarr2[$record->id]>=$ix->wperc?'success': ($uarr2[$record->id]==-1?'violet':'danger')):'gray')
                ->formatStateUsing(fn ($state,$record): string => isset($uarr2[$record->id])? ($uarr2[$record->id]==-1?'Expired':$state.'%'):'Not started'),
            Tables\Columns\TextColumn::make('quest')->label('Questions')->sortable(),
            Tables\Columns\TextColumn::make('timer')->label('Timer')->sortable()
            ->formatStateUsing(fn ($state, $record):string=> $record->type!='1'? 'Unlimited': $state),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('certi')->label('Certifications')->multiple()
                ->options(function(){
                    $arr=array_unique($this->record->exams2()->pluck('certi')->toArray());
                    $rre=count($arr)>0? Course::whereIn('id',$arr)->get():Course::has('users1')->where('pub',true)->get();
                    return $rre->pluck("name",'id')->toArray();
                })->preload(),
                Tables\Filters\SelectFilter::make('type')->label('Type')
                ->options(['0' => 'Test','1' => 'Exam']),
                ]);
    }
    protected function paginateTableQuery(Builder $query): CursorPaginator
    {
        return $query->cursorPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
