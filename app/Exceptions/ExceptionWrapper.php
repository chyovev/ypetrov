<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExceptionWrapper
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Internally Laravel throws all sorts of exceptions, each
     * with their own peculiarities.
     * For the API part of the project it's essential to have a
     * strong unified error handling, so one of the ways to
     * achieve this is to wrap all expected exceptions in an
     * ApplicationException which is then used when preparing
     * the response.
     */
    public static function wrap(Throwable $e): ApplicationException {
        return match (true) {
            $e instanceof AuthenticationException => new ApplicationException(__('exception.unauthenticated'), $e, Response::HTTP_UNAUTHORIZED),
            $e instanceof TokenMismatchException  => new ApplicationException($e->getMessage(), $e, Response::HTTP_UNAUTHORIZED),
            $e instanceof AuthorizationException  => new ApplicationException(__('exception.unauthorized'), $e, Response::HTTP_FORBIDDEN),
            $e instanceof ModelNotFoundException  => new ApplicationException(__('exception.not_found'), $e, Response::HTTP_NOT_FOUND),
            $e instanceof ValidationException     => new ApplicationException(__('exception.failed_validation'), $e, Response::HTTP_BAD_REQUEST, $e->errors()),
            $e instanceof HttpException           => new ApplicationException($e->getMessage(), $e, $e->getStatusCode()),

            // all unexpected API exceptions should have a generic
            // error message to avoid leaking of potential internal
            // information (the exception would be reported anyway,
            // so no debugging data gets lost in the process)
            default => new ApplicationException(__('exception.unexpected_error'), $e),
        };
    }

}