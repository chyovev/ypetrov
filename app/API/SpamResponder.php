<?php

namespace App\API;

use Log;
use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder as SpamResponderInterface;

class SpamResponder implements SpamResponderInterface
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All API requests are protected by Honeypot's anti-spam middleware.
     * In case an incoming request is recognized as spam, it's passed to
     * this responder which takes care of banning the current visitor.
     * From then on the request is passed onto the next middleware
     * (VisitorBanned) which in turn simply rejects the request.
     * 
     * @param Request $request
     * @param Closure $next
     */
    public function respond(Request $request, Closure $next) {
        $visitor = app(Visitor::class);

        Log::channel('api')->info("Marking visitor #{$visitor->id} as banned due to being a potential spammer");

        $visitor->markAsBanned();

        return $next($request);
    }

}
