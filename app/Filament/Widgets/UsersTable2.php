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

class UsersTable2 extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Performance summary';
    protected static ?string $pollingInterval = null;
    public $record;
    public function mount($usrec=null)
    {
        $this->record=$usrec??auth()->user();
    }
    public static function canView(): bool
    {
        return false;
       // return auth()->user()->ex!=0 || isset($this->record);
    }
    public function table(Table $table): Table
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
      //  $uarr=User::where('ex','<>',0)->where('id','<>',auth()->user()->id)->with('exams2')->get();
        $earr=Exam::has('users')->with('users')->with('modules')->get();
        $eall=$earr->pluck('id');
        $rt=Question::with('answers')->with('moduleRel')->get();

         $uarr2=[0,0,0,0,0];
            $nt=$us->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type','0')->pluck('id'))->count();
            $ne=$us->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type','1')->pluck('id'))->count();
            $uarr2[$us->id][1]=$nt;$uarr2[$us->id][2]=$ne;
            $qt=0;
            $pes=0;
            $pga=0;
            $uqt=array();
            foreach ($us->exams2 as $exa) {
                if(!empty($exa->pivot->gen) &&  in_array($exa->pivot->exam,$eall->toArray())){
           // dd(array_keys(json_decode($exa->pivot->gen,true)));
                    $res=json_decode($exa->pivot->gen,true);
                    $arrk=array_keys($res);
                    $qt+=collect($arrk)->reduce(function (?int $carry, int|string $item) {
                        return $carry + (is_int($item)?1:0);
                    });
                    $ca=0;
                    $rot=$rt->whereIn('id',$arrk);
                    foreach ($rot as $quest) {
                        $bm=$quest->answers()->where('isok',true)->count()<=1;
                        if($bm){
                            $ab=$quest->answers()->where('isok',true)->where('answers.id',$res[$quest->id][0])->count();
                            if($ab>0) {
                                $ca++; $pga++;
                                //  if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                            }
                        }else{
                            $ab2=$quest->answers()->where('isok',false)->whereIn('answers.id',$res[$quest->id])->count();
                            if($ab2==0) {
                                $ca++; $pga++;
                                // if(array_key_exists($quest->moduleRel->id,$mod)) $mod[$quest->moduleRel->id][2]++;
                            }
                        }
                    }
                  //  if($earr->where('id',$exa->exam)->count()>0) dd('dk');
                    if($earr->where('type','1')->where('id',$exa->pivot->exam)->count()>0) $pes+=(round(100*$ca/$earr->where('type','1')->where('id',$exa->pivot->exam)->first()->quest,2)>$ix->wperc?1:0);
                }
            }
            $uarr2[$us->id][0]=$qt;$uarr2[$us->id][3]=round(100*$pes/($ne>0?$ne:1),2);$uarr2[$us->id][4]=round(100*$pga/($qt>0?$qt:1),2);

        return $table
            ->query(
                User::with('vagueRel')->where('ex','<>',0)->where('id','<>',auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->description(fn (User $record): ?string => $record->email)
                    ->searchable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')->badge()
                ->formatStateUsing(fn (int $state): string => match ($state) {0 => "indigo",
                    1 => "Admin", 2 => "Starter", 3 => "User", 4 => "Pro", 5 => "VIP"})
                    ->color(fn (int $state): string => match ($state) {0 => "S. Admin",
                        1 => "gray", 2 => "info", 3 => "success", 4 => "danger", 5 => "warning"})
                ->sortable()
                ->description(fn (User $record): ?string => 'Q'.$uarr2[$record->id][0]),
                Tables\Columns\TextColumn::make('a4')->label('T. L.')->sortable()
                ->state(fn (User $record) => $uarr2[$record->id][1]),
                Tables\Columns\TextColumn::make('a1')->label('E. L.')->sortable()
                ->state(fn (User $record) => $uarr2[$record->id][2]),
                Tables\Columns\TextColumn::make('a2')->label('% P. Ex.')->sortable()
                ->state(fn (User $record) => $uarr2[$record->id][3]),
                Tables\Columns\TextColumn::make('a3')->label('% C. Ans.')->sortable()
                ->state(fn (User $record) => $uarr2[$record->id][4]),
            ]);
    }
}
