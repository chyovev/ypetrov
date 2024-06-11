<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\ItemNotFoundException;
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
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ItemNotFoundException::class,
        LikeException::class,
        VisitorBannedException::class,
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
        if ($this->shouldRephraseAsNotFound($e)) {
            abort(HttpResponse::HTTP_NOT_FOUND, 'Resource not found');
        }

        if ($e instanceof LikeException) {
            abort(HttpResponse::HTTP_CONFLICT, $e->getMessage());
        }

        if ($e instanceof TokenMismatchException) {
            abort(HttpResponse::HTTP_UNAUTHORIZED, $e->getMessage());
        }

        if ($e instanceof VisitorBannedException) {
            abort(HttpResponse::HTTP_FORBIDDEN, $e->getMessage());
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The following exceptions should be rethrown as a not-found
     * exception.
     */
    private function shouldRephraseAsNotFound(Throwable $e): bool {
        return (
               $e instanceof ModelNotFoundException // missing record in database
            || $e instanceof ItemNotFoundException  // missing record in collection (Book → Poem) 
            || $e instanceof IdentifierException    // incorrect interaction ID
        );
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
