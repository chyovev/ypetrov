<?php

namespace App\Http\Middleware;

use App\Exceptions\VisitorBannedException;
use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SpamDetector
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Visitor $visitor) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If a POST request contains the specified spam field
     * and said field is filled, the visitor gets banned.
     * 
     * @throws VisitorBannedException
     */
    public function handle(Request $request, Closure $next, string $field): Response {
        if ($request->isMethod('POST') && $request->filled($field)) {
            Log::channel('api')->info("Marking visitor #{$this->visitor->id} as banned due to being a potential spammer");

            $this->visitor->markAsBanned();

            throw new VisitorBannedException;
        }

        return $next($request);
    }
    
}