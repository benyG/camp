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
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    function render($request, Throwable $exception)
    {
        if(method_exists($exception,'getStatusCode') && $exception->getStatusCode()==419) return redirect()->to(filament()->getLoginUrl());
        if ($this->isHttpException($exception) || ($request->hasHeader('X-Livewire') && app()->environment() != 'local')) {
            return response()->view('errors.err',['exception'=>$exception]);
        }
        return parent::render($request, $exception);
     }
}
