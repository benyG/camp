<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (\Exception $e) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                session()->flash('status', __('Session has expired. Please login again.'));
                return redirect()->to(filament()->getLoginUrl());
            };
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    function render($request, Throwable $exception)
    {
        /* if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
           return redirect()->to(filament()->getLoginUrl());
        } */
        if(method_exists($exception,'getStatusCode') && $exception->getStatusCode()==419) return redirect()->to(filament()->getLoginUrl());
        if ($this->isHttpException($exception) || ($request->hasHeader('X-Livewire') && app()->environment() != 'local')) {
            return response()->view('errors.err',['exception'=>$exception]);
        }
        return parent::render($request, $exception);
     }
}
