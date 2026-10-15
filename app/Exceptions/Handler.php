<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        // A CSRF token mismatch usually means the session expired while a form
        // was open (e.g. a slow cold start on Render). Instead of dumping the
        // raw 419 page, bounce the user back to the form they submitted with
        // a friendly message so they can just resubmit.
        //
        // Laravel's base handler rewrites TokenMismatchException into a plain
        // HttpException(419, ...) in prepareException() before any renderable
        // callback runs, so this must match on the rewritten type/status
        // rather than on TokenMismatchException itself.
        $this->renderable(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            return redirect()
                ->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('status', 'Sua sessão expirou por segurança. Tente novamente.');
        });
    }
}
