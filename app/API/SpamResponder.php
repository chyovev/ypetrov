<?php

namespace App\API;

use Log;
use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Honeypot\SpamResponder\SpamResponder as SpamResponderInterface;

class SpamResponder implements SpamResponderInterface
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Visitor $visitor) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All API requests are protected by Honeypot's anti-spam middleware.
     * In case an incoming request is recognized as spam, it's passed to
     * this responder which takes care of banning the current visitor.
     * From then on the request is passed onto the next middleware
     * (VisitorBanned) which in turn simply rejects the request.
     */
    public function respond(Request $request, Closure $next): Response {
        Log::channel('api')->info("Marking visitor #{$this->visitor->id} as banned due to being a potential spammer");

        $this->visitor->markAsBanned();

        return $next($request);
    }

}
