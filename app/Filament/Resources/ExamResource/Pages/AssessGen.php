<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\Exam;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\Support\Htmlable;

#[Lazy]
class AssessGen extends Page
{
    use InteractsWithRecord;
    protected static string $resource = ExamResource::class;

    #[Locked]
    public $quest;
    #[Locked]
    public $bm2=false;
    #[Locked]
    public $aa=[];
    #[Locked]
    public $gen=[];
    #[Locked]
    public $atype;
    #[Locked]
    public $btext;
    #[Locked]
    public $qtext;
    #[Locked]
    public $qtot;
    #[Locked]
    public $qcur=0;
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
    public $cans;
    #[Locked]
    public $score=0;

    #[Validate('required',onUpdate: false,message:"No answer choosen")]
    public $ans;
    #[Validate('required',onUpdate: false,message:"No answer choosen")]
    public $ans2=[];

    protected static string $view = 'filament.resources.exam-resource.pages.assess-gen';

    public function mount($ex): void
    {
        static::authorizeResourceAccess();
        $this->record = $this->resolveRecord(Exam::has('users1')->where('name', $ex)->with('modules')->with('users1')->firstOrFail()->id);
        if(empty($this->record->users1()->first()->pivot->start_at))
                $this->record->users1()->updateExistingPivot(auth()->id(), [
                    'start_at' => now()]);
        if($this->record->type=='1'){
            if(!empty($this->record->due) && now()>$this->record->due) abort(415);
            else if(now()->diffInMinutes($this->record->users1()->first()->pivot->start_at)>
            $this->record->timer) abort(415);
        }
     //   cache()->forget('carr_'.$this->record->id.'_'.auth()->id());
     // $this->record->users1()->first()->pivot->start_at=now();
       $this->ix=cache()->rememberForever('settings', function () {
        return \App\Models\Info::findOrFail(1);
        });

        $this->carr=cache()->get('carr_'.$this->record->id.'_'.auth()->id());
        if(empty($this->carr)){
            $this->carr=cache()->remember('carr_'.$this->record->id.'_'.auth()->id(),87000, function () {
             $qt=array();
            foreach ($this->record->modules as $md) {
                $qt=  $md->questions()->pluck('id')->random($md->pivot->nb);
            }
            $rt=\App\Models\Question::whereIn('id',$qt)->with('answers')->get();
            $at=$rt->pluck('questions.text','questions.id');
                return [0,0,$this->record->timer,$this->record->name,$rt,$at];
            });
        }
            $this->tim=$this->record->timer-now()->diffInMinutes($this->record->users1()->first()->pivot->start_at);
            $this->qcur=$this->carr[0];
            $this->score=$this->carr[1];
            $this->quest=$this->carr[4];
            $this->gen=$this->carr[5];
            $this->qtot=count($this->quest);

           // $this->qcur=0;
            if($this->qcur<=$this->qtot-1){
                $this->bm1=$this->quest[$this->qcur]->answers()->where('isok',true)->count()<=1;
                $this->aa=$this->quest[$this->qcur]->answers()->pluck('text','answers.id');
                $this->qtext=$this->quest[$this->qcur]->text;
            }else{
                $sc=round(100*$this->score/$this->qtot,2);
                $this->btext="
                <div class=''>
                    <div class='text-sm text-center'>You found $this->score correct answers over $this->qtot</div> <br>
                    <div class='text-3xl text-center pb-9'>$sc % </div>
                    <div class='text-center ' style='--c-50:var(--". ($sc>=$this->ix->wperc? "success":"danger")."-50);--c-400:var(--". ( $sc>=$this->ix->wperc? "success":"danger")."-400);--c-600:var(--". ( $sc>=$this->ix->wperc? "success":"danger")."-600);' >
                    <br><span
                    class='rounded-md text-lg font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30'
                    >". ($sc>=$this->ix->wperc? "Passed":"Failed")."</span>
                   </div>
                </div> <br> <br>
                ";
            }
            $this->ico=$this->qcur<$this->qtot?'heroicon-m-play':'';
    }
    public function validateData(){
      // dd($this->qcur);
         if($this->bm1)
            $this->validate([
                'ans'=>'required',
            ]);
        else $this->validate([
            'ans2'=>'required',
        ]);
    }
    public function register($opt=false){
       $this->resetErrorBag();
       if($opt==false && $this->qcur<=$this->qtot-1) {$this->validateData();}
        if($opt || ($this->record->type=='1' && $this->record->timer-now()->diffInMinutes($this->record->users1()->first()->pivot->start_at))<0)
        {
            $this->qcur=$this->qtot; //dd($opt);
        }
        if($this->record->type=='1' || $opt ||  $this->qcur>=$this->qtot)  $this->bm2=true;
            if($this->bm2){
            if($this->qcur<=$this->qtot-2){
       $this->qcur++;
                $this->carr[0]=$this->qcur;
                $this->carr[1]=$this->score;
                $this->carr[5]=$this->gen;
                cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
                //dd($this->ans);
                $this->aa=$this->quest[$this->qcur]->answers()->pluck('text','answers.id');
                $this->bm1=$this->quest[$this->qcur]->answers()->where('isok',true)->count()<=1;
                $this->qtext=$this->quest[$this->qcur]->text;
                $this->ans=null;$this->ans2=[];
                $this->cans=null;
                $this->bm2=false;
            }else{
                $this->qcur++;
                $this->carr[0]=$this->qcur;
                $this->carr[1]=$this->score;
                $this->carr[5]=$this->gen;
                cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
                $this->record->users1()->updateExistingPivot(auth()->id(), [
                    'comp_at' => now(), 'gen'=>$this->gen]);
                $this->aa=[];
                $this->qtext=null;
                $this->ans=null;$this->ans2=[];
                $this->cans=null;
                $this->bm2=false;
                $sc=round(100*$this->score/$this->qtot,2);
                $this->btext="
                <div class=''>
                    <div class='text-sm text-center'>You found $this->score correct answers over $this->qtot</div> <br>
                    <div class='text-3xl text-center pb-9'>$sc % </div>
                    <div class='text-center ' style='--c-50:var(--". ($sc>=$this->ix->wperc? "success":"danger")."-50);--c-400:var(--". ( $sc>=$this->ix->wperc? "success":"danger")."-400);--c-600:var(--". ( $sc>=$this->ix->wperc? "success":"danger")."-600);' >
                    <br><span
                    class='rounded-md text-lg font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30'
                    >". ($sc>=$this->ix->wperc? "Passed":"Failed")."</span>
                   </div>
                </div> <br> <br>
                ";

                if($this->qcur>$this->qtot)return redirect()->to(ExamResource::getUrl());
           //
            }
        }else{
            if($this->qcur<=$this->qtot-1){
                if($this->bm1){
                    $ab=$this->quest[$this->qcur]->answers()->where('isok',true)->where('answers.id',$this->ans)->count();
                    $au=$this->quest[$this->qcur]->answers()->where('isok',true)->first()->text;
                    $this->gen[$this->quest[$this->qcur]->id]=[$this->ans];
                    if($ab>0) $this->score++;
                    if($this->record->type=='0' && $this->bm2==false)
                    $this->cans=$ab>0?"
                    <span class='text-primary-600 text-xs'> <br>
            Correct answer</span>":
                    "<span style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-custom-600 text-xs'>
                    <br>  Wrong answer <br> This was the correct answer : <br> $au</span>";
                }else{
                    $ab2=$this->quest[$this->qcur]->answers()->where('isok',false)->whereIn('answers.id',$this->ans2)->count();
                    $au2=$this->quest[$this->qcur]->answers()->where('isok',true)->pluck('answers.text');
                    $this->gen[$this->quest[$this->qcur]->id]=$this->ans2;
                // dd($au2);
                if($this->record->type=='0' && $this->bm2==false)
                if($ab2==0) $this->score++;
                    $this->cans=$ab2==0?"
                    <span class='text-primary-600 text-xs'> <br>
            Correct set of answers</span>":
                    "<span style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-custom-600 text-xs'>
                    <br>  Wrong set of answers <br> This was the correct set  : <br> ".$au2->join("<br>")."</span>";
                }
                $this->bm2=true;
            }
        }
    }

    public function closeComp()
    {
       $this->register(true);
    }
    public function getTitle() : string | Htmlable{
        return $this->record->from !=auth()->id()?'Class Examiniation':($this->record->type==0?"Test your knowlegde":'Exam Simulation');
    }
    public function getSubheading() : string | Htmlable{
        return "Passing score : ".$this->ix->wperc." | Timer: ".$this->record->timer." min";
    }

}
