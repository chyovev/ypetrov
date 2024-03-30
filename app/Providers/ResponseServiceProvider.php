<?php

namespace App\Providers;

use Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\ServiceProvider;

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
        Response::macro('ok', function(array $properties = [], int $code = HttpResponse::HTTP_OK) {
            $message  = HttpResponse::$statusTexts[$code];
            $response = [
                'success' => true,
                'code'    => $code,
                'message' => $message,
                'errors'  => [],
            ];

            $response = array_merge($response, $properties);

            return Response::make($response)->setStatusCode($code);
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Used when deleting an object through the API.
     */
    private function registerEmptyResponse(): void {
        Response::macro('empty', function () {
            return Response::make()->setStatusCode(HttpResponse::HTTP_NO_CONTENT);
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    private function registerErrorResponse(): void {
        Response::macro('error', function (array $errors, int $code = HttpResponse::HTTP_BAD_REQUEST) {
            $message  = HttpResponse::$statusTexts[$code];
            $response = [
                'success' => false,
                'code'    => $code,
                'message' => $message,
                'errors'  => $errors,
            ];

            return Response::make($response)->setStatusCode($code);
        });
    }
}
