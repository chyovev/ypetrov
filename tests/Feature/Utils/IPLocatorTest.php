<?php

namespace Tests\Feature\Utils;

use Tests\TestCase;
use App\Utils\IPLocator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class IPLocatorTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_successful_api_call(): void {
        Http::fake([
            '*' => Http::response(['countryCode' => 'HR']),
        ]);

        $ip   = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';
        $code = $this->app->make(IPLocator::class)->locate($ip);

        $this->assertSame('HR', $code);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_successful_api_call_no_response(): void {
        Http::fake([
            '*' => Http::response([]),
        ]);

        $ip   = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';
        $code = $this->app->make(IPLocator::class)->locate($ip);

        $this->assertNull($code);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_failed_api_call(): void {
        Http::fake([
            '*' => Http::response(['countryCode' => 'HR'], Response::HTTP_TOO_MANY_REQUESTS),
        ]);

        $this->expectException(RequestException::class);

        $ip = 'dcd3:1001:ffed:13bb:516c:51e2:c372:2304';

        $this->app->make(IPLocator::class)->locate($ip);
    }

}
