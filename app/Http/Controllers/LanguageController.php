<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class LanguageController extends Controller
{
    public function langS(Request $request){
        $lg=$request->input('lang');
        session(['lang'=>$lg]);
        setcookie("lang", $lg, time() +60*60*24*785200);

        return redirect()->back()->with(['lang'=>$lg]);
    }
    public function langS2($lg){
     //   session_start();
        session()->put('lang',$lg);
        setcookie("lang", $lg, time() +60*60*24*785200);
        App::setLocale($lg);

        return redirect()->back();
    }

}
