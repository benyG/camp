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

class UsersTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Users summary';
    protected static ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        $ix=cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        $uarr=User::where('ex','<>',0)->where('id','<>',auth()->user()->id)->with('exams2')->get();
        $uarr2=array();
        $earr=Exam::has('users')->with('users')->get();
        $eall=$earr->pluck('id');
        $rt=Question::with('answers')->with('moduleRel')->get();
     //   $etest=$earr->where('type','0')->pluck('id');
     //   $eexam=$earr->pluck('id');
        foreach ($uarr as $us) {
      //if($us->id==15)  dd($userex);
         $uarr2[$us->id]=[0,0,0,0,0];
            $nt=$us->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type','0')->pluck('id'))->count();
            $ne=$us->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type','1')->pluck('id'))->count();
            $uarr2[$us->id][1]=$nt;$uarr2[$us->id][2]=$ne;
            $qt=0;
            $pes=0;
            $pga=0;
            $uqt=array();
            if($us->exam2!=null)
            foreach ($us->exam2 as $exa) {

            }
        }
           // dd($uarr2);
        return $table
            ->query(
                User::with('vagueRel')->where('ex','<>',0)->where('id','<>',auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ex')->label('Type')->badge()
                ->formatStateUsing(fn (int $state): string => match ($state) {0 => "indigo",
                    1 => "Admin", 2 => "Starter", 3 => "User", 4 => "Pro", 5 => "VIP"})
                    ->color(fn (int $state): string => match ($state) {0 => "S. Admin",
                        1 => "gray", 2 => "info", 3 => "success", 4 => "danger", 5 => "warning"})
                ->sortable(),
                Tables\Columns\TextColumn::make('a5')->label('Q. Answered')->sortable(),
                Tables\Columns\TextColumn::make('a4')->label('Tests Launched')->sortable()
                ->state(fn (User $record) => $uarr2[$record->id][1]),
                Tables\Columns\TextColumn::make('a1')->label('Exams Launched')->sortable(),
                Tables\Columns\TextColumn::make('a2')->label('% Passed exams')->sortable(),
                Tables\Columns\TextColumn::make('a3')->label('% Correct ans.')->sortable(),
            ]);
    }
    public static function canView(): bool
    {
        return auth()->user()->ex==0;
    }
}
