<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            /** @var \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $exception */
            return response()->view("errors.{$exception->getStatusCode()}", [], $exception->getStatusCode());
        }

        if ($exception instanceof TokenMismatchException) {

            Log::channel('auth')->warning('419 Page Expired (CSRF Token Mismatch) detected', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);

        }
        return parent::render($request, $exception);
    }
}
