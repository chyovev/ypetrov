<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Throwable;
use Log;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Overwrite the parent render method for ValidationException
     */
    public function render($request, Throwable $e) {
        $this->rephraseException($e);

        if ($this->shouldServeJson($request)) {
            return $this->serveJsonError($e);
        }
        
        return parent::render($request, $e);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Some exceptions need to be 'rephrased', i.e. rethrown as another
     * exception class in order to be handled correctly down the line.
     * 
     * @throws HttpException
     * @param  Throwable $e
     */
    private function rephraseException(Throwable $e): void {
        if ($e instanceof ModelNotFoundException || $e instanceof IdentifierException) {
            // if an interactive object's identifier could not be
            // decoded for some reason, log the exception it its entirety
            if ($e instanceof IdentifierException) {
                Log::info($e);
            }

            abort(HttpResponse::HTTP_NOT_FOUND, 'Resource not found');
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Exceptions which occur on API routes should be transformed
     * to a JSON response.
     * 
     * @param  $request – request which lead to the exception
     * @return bool
     */
    private function shouldServeJson($request): bool {
        return $request->is('api/*');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Extract all errors from the exception and pass them to the
     * macro 'error' function defined in the response service provider.
     * 
     * @param  Throwable $e
     * @return Response
     */
    private function serveJsonError(Throwable $e) {
        // form request validation
        if ($e instanceof ValidationException) {
            $errors = $e->errors();
            $code   = Response::HTTP_BAD_REQUEST;
        }

        // throttle requests or general abort() 
        elseif ($e instanceof HttpException) {
            $errors = $this->getErrorMessage($e);
            $code   = $e->getStatusCode();
        }
        
        // all other cases
        else {
            $errors = $this->getErrorMessage($e);
            $code   = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->error($errors, $code);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Extract the error message from an exception and
     * return it as an array using the 'generic' key.
     * 
     * @return string[]
     */
    private function getErrorMessage(Throwable $e): array {
        return ['generic' => $e->getMessage()];
    }

}
