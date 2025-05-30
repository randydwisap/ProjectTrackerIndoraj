<?php

namespace App\Exceptions;
use Illuminate\Database\QueryException;

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

    public function render($request, Throwable $exception)
{
    if ($exception instanceof QueryException && str_contains($exception->getMessage(), 'max_connections_per_hour')) {
        return response()->view('errors.db-limit', [], 429); // kode 429 = Too Many Requests
    }

    return parent::render($request, $exception);
}
}
