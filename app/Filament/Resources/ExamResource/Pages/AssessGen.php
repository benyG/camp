<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
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
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;

#[Lazy]
class AssessGen extends Page implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $resource = ExamResource::class;
    public Exam $record;
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
    public $qcur2=1;
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
    public $score=0;
    #[Locked]
    public $qid;
    #[Locked]
    public $aid;

    #[Validate('required',onUpdate: false,message:"No answer choosen")]
    public $ans;
    #[Validate('required',onUpdate: false,message:"No answer choosen")]
    public $ans2=[];

    protected static string $view = 'filament.resources.exam-resource.pages.assess-gen';

    public function mount($ex): void
    {
        $this->record = Exam::has('users1')->where('name', $ex)->with('modules')->with('users1')->firstOrFail();
        if(empty($this->record->users1()->first()->pivot->start_at))
                $this->record->users1()->updateExistingPivot(auth()->id(), [
                    'start_at' => now()]);
        if($this->record->type=='1'){
            if(!empty($this->record->due) && now()>$this->record->due) redirect()->to(ExamResource::getUrl());
            else if(now()->diffInMinutes($this->record->users1()->first()->pivot->start_at)>
            $this->record->timer) redirect()->to(ExamResource::getUrl());
        }
      //  cache()->forget('carr_'.$this->record->id.'_'.auth()->id());
     // $this->record->users1()->first()->pivot->start_at=now();
       $this->ix=cache()->rememberForever('settings', function () {
        return \App\Models\Info::findOrFail(1);
        });

        $this->carr=cache()->get('carr_'.$this->record->id.'_'.auth()->id());
        if(empty($this->carr)){
            $this->carr=cache()->remember('carr_'.$this->record->id.'_'.auth()->id(),87000, function () {
             $qt=array();
            foreach ($this->record->modules as $md) {
                $qt= array_merge($qt,$md->questions()->pluck('id')->random($md->pivot->nb)->toArray());
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

       //$this->qcur=0;
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
            $this->qcur2=$this->qcur;
    }
    public function revAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('rev')->label('here')->link()
            ->requiresConfirmation()->color('warning')->size(ActionSize::Small)
            ->modalIcon('heroicon-o-question-mark-circle')
            ->modalHeading('Question Review')
            ->modalDescription('Do you want to request a review of this question?')
            ->action(function () {
                $rev = new \App\Models\Review;
                $rev->user=auth()->id();
                $rev->quest=$this->qid;
                $rev->ans=json_encode($this->aid);
                $rev->save();
                Notification::make()->success()->title('Review submitted.')->send();
            });
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
    public function populate(){
        $this->qcur2++;
        $this->ico=$this->qcur<$this->qtot?'heroicon-m-play':'';
                if($this->qcur<=$this->qtot-1){
                    $this->aa=$this->quest[$this->qcur]->answers()->pluck('text','answers.id');
                    $this->bm1=$this->quest[$this->qcur]->answers()->where('isok',true)->count()<=1;
                    $this->qtext=$this->quest[$this->qcur]->text;
                    $this->ans=null;$this->ans2=[];
                    $this->cans=null;
                    $this->bm2=false;
                }else{
                    $this->carr[0]=$this->qcur;
                    $this->carr[1]=$this->score;
                    cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
                    $this->record->users1()->updateExistingPivot(auth()->id(), [
                        'comp_at' => now()]);
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
                    if($this->qcur2>$this->qtot)return redirect()->to(ExamResource::getUrl());
                    $this->qcur2++;
            //
                }

      }
    public function incrQuest(){
            if($this->bm1){
                $ab=$this->quest[$this->qcur]->answers()->where('isok',true)->where('answers.id',$this->ans)->count();
                $au="";
                if($this->quest[$this->qcur]->answers()->where('isok',true)->count()>0)
                $au=$this->quest[$this->qcur]->answers()->where('isok',true)->first()->text;

                $this->gen[$this->quest[$this->qcur]->id]=[$this->ans];
                $this->aid=[$this->ans];$this->qid=$this->quest[$this->qcur]->id;
                if($ab>0) $this->score++;
                if($this->record->type=='0' && $this->bm2==false)
                // DO NOT REMOVE ALT='', CODE NEEDED FOR REVIEW BUTTON
                $this->cans=$ab>0?"
                <span class='text-sm text-primary-600'> <br>
        Correct answer</span>":
                "<span alt='' style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-sm text-custom-600'>
                <br>  Wrong answer <br> </span><span class='text-xs'>This was the correct answer : <br>
                $au</span>";
            }else{
                $ab2=$this->quest[$this->qcur]->answers()->where('isok',false)->whereIn('answers.id',$this->ans2)->count();
                $au2="";
                if($this->quest[$this->qcur]->answers()->where('isok',true)->count()>0)
                $au2=$this->quest[$this->qcur]->answers()->where('isok',true)->pluck('answers.text');
                $this->gen[$this->quest[$this->qcur]->id]=$this->ans2;
                $this->aid=$this->ans2;$this->qid=$this->quest[$this->qcur]->id;
            if($this->record->type=='0' && $this->bm2==false)
            if($ab2==0) $this->score++;
             // DO NOT REMOVE ALT='', CODE NEEDED FOR REVIEW BUTTON
                $this->cans=$ab2==0?"
                <span class='text-sm text-primary-600'> <br>
        Correct set of answers</span>":
                "<span alt='' style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class='text-sm text-custom-600'>
                <br>  Wrong set of answers <br> </span><span class='text-xs'>This was the correct set : <br>
                ".$au2->join("<br>")."</span>";
            }
            $this->bm2=true;
            $this->qcur++;
            $this->carr[0]=$this->qcur;
            $this->carr[1]=$this->score;
            $this->carr[5]=$this->gen;
            cache(['carr_'.$this->record->id.'_'.auth()->id() => $this->carr], 86400);
            $this->record->users1()->updateExistingPivot(auth()->id(), [
                'gen'=>$this->gen]);

      }
    public function register($opt=false){
       $this->resetErrorBag();
       if($opt==false && $this->qcur<=$this->qtot-1) {$this->validateData();}
        if($opt || ($this->record->type=='1' && $this->record->timer-now()->diffInMinutes($this->record->users1()->first()->pivot->start_at))<0)
        {
            $this->qcur=$this->qtot; //dd($opt);
            $this->qcur2=$this->qtot-1; //dd($opt);
        }
        if($this->qcur>=$this->qtot){
            $this->populate();
        }else {
            if($this->record->type=='1'){
                $this->incrQuest();
                $this->populate();
            }
            else{
                if($this->bm2){
                    $this->populate();
                }else $this->incrQuest();
            }
        }
    }

    public function closeComp()
    {
       $this->register(true);
    }
    public function getTitle() : string | Htmlable{
        return $this->record->type==0?'Test your knowlegde':($this->record->from !=auth()->id()?"Class Examiniation":'Exam Simulation');
    }
    public function getSubheading() : string | Htmlable{
        return "Certification : ".$this->record->certRel->name." | Passing score : ".$this->ix->wperc.($this->record->type=='0'?"":"| Timer: ".$this->record->timer." min");
    }

}
