<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\LogRequest;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LogRequestTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_request_logging(): void {
        $visitor = (new Visitor)->forceFill(['id' => 1]);
        $this->app->instance(Visitor::class, $visitor);

        Route::get('/test', fn() => 'test')->middleware(LogRequest::class);

        $mock = Log::spy();

        $mock->shouldReceive('channel')->andReturn($mock);

        $response = $this->get('/test');
        $response->assertStatus(200);

        $expected = '[GET] (Visitor #1) /test [Closure]';

        $mock->shouldHaveReceived('info', [$expected]);
    }

}