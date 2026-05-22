<?php

namespace App\Providers;

use App\Exceptions\ExceptionWrapper;
use App\Exceptions\ApplicationException;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ResponseServiceProvider extends ServiceProvider
{

    ///////////////////////////////////////////////////////////////////////////
    public function register(): void {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    public function boot(): void {
        $this->registerOkResponse();
        $this->registerEmptyResponse();
        $this->registerErrorResponse();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function registerOkResponse(): void {
        response()->macro('ok', function(array $properties = [], int $code = Response::HTTP_OK): Response {
            $message  = Response::$statusTexts[$code];
            $response = [
                'message' => $message,
            ];

            $response = array_merge($response, $properties);

            return response()->make($response)->setStatusCode($code);
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Used when deleting an object through the API.
     */
    private function registerEmptyResponse(): void {
        response()->macro('empty', fn(): Response  => 
            response()->make()->setStatusCode(Response::HTTP_NO_CONTENT)
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    private function registerErrorResponse(): void {
        response()->macro('error', function (Throwable $e): Response {
            if ( ! is_a($e, ApplicationException::class)) {
                $e = ExceptionWrapper::wrap($e);
            }

            $response = [
                'message' => $e->getMessage(),
                'errors'  => $e->getErrors(),
            ];

            return response()->make($response)->setStatusCode($e->getCode());
        });
    }
}
