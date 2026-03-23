<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Handle unauthenticated exceptions.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Always return JSON 401 for API requests
        return response()->json([
            'message' => $exception->getMessage()
        ], 401);
    }
}
