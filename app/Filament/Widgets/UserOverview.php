<?php

namespace App\Filament\Widgets;

use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Filament\Widgets\Widget;

class UserOverview extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = true;

    protected static string $view = 'filament.widgets.us-ovv';
    // protected int | string | array $columnSpan = 'full';

    #[Locked]
    public $va;

    #[Locked]
    public $co;

    #[Locked]
    public $mo;

    #[Locked]
    public $qu;

    #[Locked]
    public $iac;

    #[Locked]
    public $co1;

    #[Locked]
    public $co2;

    public function mount(): void
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
        });
        //  $uarr=User::where('ex','<>',0)->where('id','<>',auth()->user()->id)->with('exams2')->get();
        $earr = Exam::has('users1')->with('users1')->get();
        $eall = $earr->pluck('id');
        $rt = Question::with('answers')->get();
        $nt = auth()->user()->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type', '0')->pluck('id'))->count();
        $ne = auth()->user()->exams2()->whereNotNull('start_at')->pluck('exam')->intersect($earr->where('type', '1')->pluck('id'))->count();
        $this->co = $nt;
        $this->mo = $ne;
        $qt = 0;
        $pes = 0;
        $pga = 0;
        $uqt = [];
        foreach (auth()->user()->exams2 as $exa) {
            if (! empty($exa->pivot->gen) && in_array($exa->pivot->exam, $eall->toArray())) {
                $res = $exa->pivot->gen;
                $arrk = array_keys($res);
                $qt += collect($arrk)->reduce(function (?int $carry, int|string $item) {
                    return $carry + (is_int($item) ? 1 : 0);
                });
                $ca = 0;
                $rot = $rt->whereIn('id', $arrk);
                foreach ($rot as $quest) {
                    $bm = $quest->answers()->where('isok', true)->count() <= 1;
                    if ($bm) {
                        $ab = $quest->answers()->where('isok', true)->where('answers.id', $res[$quest->id][0])->count();
                        if ($ab > 0) {
                            $ca++;
                            $pga++;
                        }
                    } else {
                        $ab2 = $quest->answers()->where('isok', false)->whereIn('answers.id', $res[$quest->id])->count();
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
        $this->va = $qt;
        $this->qu = round(100 * $pes / ($ne > 0 ? $ne : 1), 2);
        $this->iac = round(100 * $pga / ($qt > 0 ? $qt : 1), 2);
        $this->co1 = $this->qu > 0 ? 'primary' : 'danger';
        $this->co2 = $this->iac >= 50 ? ($this->iac >= 70 ? 'success' : 'warning') : 'danger';
    }

    public static function canView(): bool
    {
        return auth()->user()->ex > 1;
    }
}
