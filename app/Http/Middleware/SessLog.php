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
        if (abs(now()->diffInMinutes(session('lastActivityTime'))) >= 60 || (auth()->check() && auth()->user()->ex > 1 && \App\Models\User::where('id', auth()->id())->first()->kx != auth()->user()->kx)) {
            if (auth()->check()) {
                \App\Models\Journ::add(auth()->user(), 'Login', 10, 'Session expiration. Loging out');
                if (auth()->user()->ex > 6) {
                    \App\Models\User::destroy(auth()->id());
                }
                auth()->logout();
                session()->forget('lastActivityTime');
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->to(filament()->getLoginUrl());
            }
        }
        session(['lastActivityTime' => now()]);

        return $next($request);
    }
}
