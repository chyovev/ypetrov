<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use App\Exceptions\VisitorBannedException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorBanned
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Visitor $visitor) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If a visitor has been banned, they can lose access
     * to certain sections of the website.
     * 
     * NB! This middleware can only be called after the RegisterVisitor
     *     one in order for the visitor to be registered as a shared
     *     instance.
     * 
     * @throws VisitorBannedException
     */
    public function handle(Request $request, Closure $next): Response {
        if ($this->visitor->is_banned) {
            throw new VisitorBannedException(__('global.you_have_been_banned'));
        }

        return $next($request);
    }
}
