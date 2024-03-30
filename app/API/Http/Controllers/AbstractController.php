<?php

namespace App\API\Http\Controllers;

use Log;
use App\Models\Visitor;
use App\Http\Controllers\Controller;

abstract class AbstractController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct() {
        // console run commands (such as php artisan route:list)
        // should not be logged as there are no actual requests
        if ( ! app()->runningInConsole()) {
            $this->logRequest();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Log the incoming request (regardless of whether it's successful
     * or not) using the following data:
     *     - HTTP method (POST/GET/DELETE, etc.)
     *     - Visitor ID
     *     - full URL of the request
     *     - endpoint action
     * 
     * @return void
     */
    private function logRequest(): void {
        $method  = request()->method();
        $url     = request()->url();
        $action  = class_basename(request()->route()->getActionName());
        $visitor = app(Visitor::class);

        $message  = "[{$method}] (Visitor #{$visitor->id}) {$url} [{$action}]";

        Log::channel('api')->info($message);
    }

}