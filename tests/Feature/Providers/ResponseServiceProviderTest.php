<?php

namespace Tests\Feature\Providers;

use App\Exceptions\LikeException;
use Exception;
use Illuminate\Http\Response;
use Tests\TestCase;

class ResponseServiceProviderTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_ok_response(): void {
        /** @var Response */
        $response = response()->ok(['message' => 'Success!']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"message":"Success!"}', $response->getContent());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_ok_response_different_code(): void {
        /** @var Response */
        $response = response()->ok(['message' => 'Success!'], Response::HTTP_CREATED);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('{"message":"Success!"}', $response->getContent());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_empty_response(): void {
        /** @var Response */
        $response = response()->empty();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_error_response(): void {
        $exception = new LikeException('Test');

        /** @var Response */
        $response = response()->error($exception);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(409, $response->getStatusCode());
        $this->assertSame('{"message":"Test","errors":{"generic":["Test"]}}', $response->getContent());
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_error_response_wrapped(): void {
        $exception = new Exception('Test');

        /** @var Response */
        $response = response()->error($exception);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame(json_encode(['message' => 'Възникна непредвидена грешка. Моля, опитайте по-късно.', 'errors' => ['generic' => ['Възникна непредвидена грешка. Моля, опитайте по-късно.']]]), $response->getContent());
    }

}