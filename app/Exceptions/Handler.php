<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ApplicationException::class,
    ];

    ///////////////////////////////////////////////////////////////////////////
    public function render($request, Throwable $e) {
        return $request->is('api/*')
            ? response()->error($e)
            : parent::render($request, $e);
    }
}
