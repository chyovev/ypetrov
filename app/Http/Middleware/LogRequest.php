<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;

class LogRequest
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Router $router, private Visitor $visitor) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    public function handle(Request $request, Closure $next): Response {
        $this->logRequest($request);

        return $next($request);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function logRequest(Request $request) {
        $method   = $request->method();
        $url      = $request->getPathInfo();
        $endpoint = class_basename($this->router->getCurrentRoute()->getActionName());

        $message  = "[{$method}] (Visitor #{$this->visitor->id}) {$url} [{$endpoint}]";

        Log::channel('api')->info($message);
    }

}
