<?php

namespace Tests\Feature\Http\Middleware;

use App\Exceptions\VisitorBannedException;
use App\Http\Middleware\VisitorBanned;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class VisitorBannedTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_not_banned_request_passes(): void {
        $visitor = Visitor::factory()->make(['is_banned' => false]);
        $this->app->instance(Visitor::class, $visitor);

        $request = $this->app->make(Request::class);
        $next    = function(): Response { return new Response; };

        $middleware = $this->app->make(VisitorBanned::class);
        $middleware->handle($request, $next);

        $this->addToAssertionCount(1);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_banned_request_interrupted(): void {
        $visitor = Visitor::factory()->make(['is_banned' => true]);
        $this->app->instance(Visitor::class, $visitor);
        
        $request = $this->app->make(Request::class);
        $next    = function(): Response { return new Response; };

        $this->expectException(VisitorBannedException::class);

        $middleware = $this->app->make(VisitorBanned::class);
        $middleware->handle($request, $next);
    }

}