<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function langS(Request $request){
        $lg=$request->input('lang');
        session(['lang'=>$lg]);
        setcookie("lang", $lg, time() +60*60*24*785200);
        return redirect()->back()->with(['lang'=>$lg]);
    }
    public function langS2($lg){
        session(['lang'=>$lg]);
        setcookie("lang", $lg, time() +60*60*24*785200);
        return redirect()->back()->with(['lang'=>$lg]);
    }

}
