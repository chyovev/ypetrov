<?php

namespace Tests\Feature;

use BadMethodCallException;
use Tests\TestCase;
use App\Helpers\IPLocator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class IPLocatorTest extends TestCase
{

    /**
     * Even though all API calls will be mocked for the tests below
     * making the validity of the passed IP address irrelevant,
     * it's still nice to use faker to generate an IP address.
     */
    use WithFaker;

    ///////////////////////////////////////////////////////////////////////////
    public function setUp(): void {
        // call parent to set up test environment
        parent::setUp();

        $this->setUpFaker();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once the IPLocator object gets initiated, an API call
     * is immediately fired in the constructor which might
     * throw an exception in case of a failure.
     * Therefore, if no exception is thrown, the API call
     * is considered successful.
     */
    public function test_successful_api_call(): void {
        $this->setUpFakeSuccessResponse();

        $ip      = $this->faker->ipv6();
        $locator = $this->initIPLocator($ip);

        $this->assertNotNull($locator);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setUpFakeSuccessResponse(): void {
        $response = ['countryCode' => 'HR'];
        $code     = Response::HTTP_OK;

        $this->setUpFakeResponse($response, $code);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Set up a fake response from the API.
     * 
     * @param  array<string,string> $data – expected response data
     * @param  int $code – HTTP status code response
     * @return void
     */
    private function setUpFakeResponse(array $data, int $code): void {
        Http::fake([
            '*' => Http::response($data, $code),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws RequestException – could throw exception for unsuccessful requests
     */
    private function initIPLocator(string $ip): IPLocator {
        return new IPLocator($ip);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If for some reason the API request is unsuccessful,
     * an exception will be thrown.
     */
    public function test_unsuccessful_api_call(): void {
        $this->expectException(RequestException::class);
        
        $this->setUpFakeErrorResponse();

        $ip = $this->faker->ipv6();

        $this->initIPLocator($ip);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function setUpFakeErrorResponse(): void {
        $response = [];
        $code     = Response::HTTP_TOO_MANY_REQUESTS;

        $this->setUpFakeResponse($response, $code);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Data returned from the API can be accessed via magic getter
     * methods. A getter method can work only if it matches the name
     * of one of the fields requested from the API.
     */
    public function test_successful_magic_getter_method_call(): void {
        $this->setUpFakeSuccessResponse();

        $ip      = $this->faker->ipv6();
        $locator = $this->initIPLocator($ip);

        $countryCode = $locator->getCountryCode();
        $this->assertSame('HR', $countryCode);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Alternatively, if one tries to fetch data which does
     * not correspond with one of the fields requested from
     * the API, an exception will be thrown.
     */
    public function test_unsuccessful_magic_getter_method_call(): void {
        $this->setUpFakeSuccessResponse();

        $ip      = $this->faker->ipv6();
        $locator = $this->initIPLocator($ip);

        $this->expectException(BadMethodCallException::class);
        $locator->getIP();
    }
}
