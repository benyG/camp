<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class PageController extends Controller
{
    //
    public function show(): View
    {
        return view('welcome', [
            'user' => Course::find(4)->modules->first()->name,
        ]);
    }
}
