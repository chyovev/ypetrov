<?php

namespace Tests\Feature\Http\Middleware;

use App\Helpers\IPLocator;
use App\Http\Middleware\RegisterVisitor;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\MockInterface;
use Tests\TestCase;

class RegisterVisitorTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_registering_visitor(): void {
        $this->assertNull(app(Visitor::class)->id);

        $this->mock(Request::class, function(MockInterface $mock): void {
            $mock->shouldReceive('ip')->andReturn('dcd3:1001:ffed:13bb:516c:51e2:c372:2304');
        });

        $this->mock(IPLocator::class, function(MockInterface $mock): void {
            $mock->shouldReceive('locate')->andReturn('BG');
        });

        $request = $this->app->make(Request::class);

        $middleware = $this->app->make(RegisterVisitor::class);
        $middleware->handle($request, function(): Response { return new Response; });

        $this->assertNotNull(app(Visitor::class)->id);
        $this->assertSame('BG', app(Visitor::class)->country_code);
    }

}