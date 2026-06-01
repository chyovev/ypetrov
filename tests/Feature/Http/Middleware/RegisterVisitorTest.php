<?php

namespace Tests\Feature\Http\Middleware;

use Exception;
use App\Utils\IPLocator;
use App\Http\Middleware\RegisterVisitor;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\TestCase;

class RegisterVisitorTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_registering_new_visitor(): void {
        $this->assertNull($this->app->make(Visitor::class)->id);

        $this->mock(Request::class, function(MockInterface $mock): void {
            $mock->shouldReceive('ip')->andReturn('dcd3:1001:ffed:13bb:516c:51e2:c372:2304');
        });

        $this->mock(IPLocator::class, function(MockInterface $mock): void {
            $mock->shouldReceive('locate')->andReturn('BG');
        });

        $request = $this->app->make(Request::class);

        $middleware = $this->app->make(RegisterVisitor::class);
        $middleware->handle($request, fn(): Response => new Response );

        $this->assertNotNull($this->app->make(Visitor::class)->id);
        $this->assertSame('BG', $this->app->make(Visitor::class)->country_code);
        $this->assertTrue($this->app->make(Visitor::class)->wasRecentlyCreated);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_registering_existing_visitor(): void {
        Carbon::setTestNow('2026-01-01');

        $ip       = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';
        $existing = Visitor::factory()->create([
            'ip_hash'      => $ip,
            'country_code' => 'BG',
        ]);

        $this->assertSame('2026-01-01', $existing->last_visit_date->format('Y-m-d'));
        $this->assertNull($this->app->make(Visitor::class)->id);

        $this->mock(Request::class, function(MockInterface $mock) use($ip): void {
            $mock->shouldReceive('ip')->andReturn($ip);
        });

        $request = $this->app->make(Request::class);
        
        Carbon::setTestNow('2026-05-31');

        $middleware = $this->app->make(RegisterVisitor::class);
        $middleware->handle($request, fn(): Response => new Response );

        $this->assertSame($existing->id, $this->app->make(Visitor::class)->id);
        $this->assertFalse($this->app->make(Visitor::class)->wasRecentlyCreated);
        $this->assertSame('2026-05-31', $this->app->make(Visitor::class)->last_visit_date->format('Y-m-d'));
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_registering_existing_visitor_updating_missing_country_code(): void {
        $ip       = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';
        $existing = Visitor::factory()->create([
            'ip_hash'      => $ip,
            'country_code' => null,
        ]);

        $this->assertNull($existing->country_code);
        $this->assertNull($this->app->make(Visitor::class)->id);

        $this->mock(Request::class, function(MockInterface $mock) use($ip): void {
            $mock->shouldReceive('ip')->andReturn($ip);
        });

        $this->mock(IPLocator::class, function(MockInterface $mock): void {
            $mock->shouldReceive('locate')->andReturn('BG');
        });

        $request = $this->app->make(Request::class);
        
        Carbon::setTestNow('2026-05-31');

        $middleware = $this->app->make(RegisterVisitor::class);
        $middleware->handle($request, fn(): Response => new Response );

        $this->assertSame($existing->id, $this->app->make(Visitor::class)->id);
        $this->assertSame('BG', $this->app->make(Visitor::class)->country_code);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_registering_existing_visitor_updating_missing_country_code_failure(): void {
        $ip       = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';
        $existing = Visitor::factory()->create([
            'ip_hash'      => $ip,
            'country_code' => null,
        ]);

        $this->assertNull($existing->country_code);
        $this->assertNull($this->app->make(Visitor::class)->id);

        $this->mock(Request::class, function(MockInterface $mock) use($ip): void {
            $mock->shouldReceive('ip')->andReturn($ip);
        });

        $this->mock(IPLocator::class, function(MockInterface $mock): void {
            $mock->shouldReceive('locate')->andThrow(new Exception);
        });

        $request = $this->app->make(Request::class);
        
        Carbon::setTestNow('2026-05-31');

        $middleware = $this->app->make(RegisterVisitor::class);
        $middleware->handle($request, fn(): Response => new Response );

        $this->assertSame($existing->id, $this->app->make(Visitor::class)->id);
        $this->assertNull($this->app->make(Visitor::class)->country_code);
    }

}