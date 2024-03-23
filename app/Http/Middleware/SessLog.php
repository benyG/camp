<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('lastActivityTime')) {
            session(['lastActivityTime' => now()]);
        }
        if (now()->diffInMinutes(session('lastActivityTime')) >= 120 ) {
            if (auth()->check() && auth()->id() > 1) {
                \App\Models\Journ::add(auth()->user(),'Login',10,"Session expiration. Loging out");
               auth()->logout();
               session()->forget('lastActivityTime');
               return redirect()->to(filament()->getLoginUrl());
           }
       }
       session(['lastActivityTime' => now()]);
        return $next($request);
    }
}
