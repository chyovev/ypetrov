<?php

namespace App\Helpers;

use BadMethodCallException;
use Illuminate\Support\Str;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * The IPLocator class is used as wrapper for the
 * IP Geolocation API project – it fetches location
 * data for a specific IP address and allows the user
 * to access the response properties via magic getter
 * methods (see __call() overloading method below).
 * 
 * @see https://ip-api.com/
 */

class IPLocator
{

    /**
     * The IP address being inspected.
     * 
     * @var string
     */
    private string $ip;

    /**
     * Response from the API call, gets stored
     * as a property in its entirety while magic
     * getter methods extract data from it.
     * 
     * @var Response
     */
    private $response;

    
    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $ip) {
        $this->ip = $ip;

        $this->parseIP();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function parseIP(): void {
        $this->response = $this->fetchDataForIPFromAPI();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch data from the remote API.
     * 
     * NB! Keep in mind that the request might not be successful
     *     at which point a RequestException will be thrown due
     *     to the throw() method.
     *     For instance, as of this writing, there's a limit of
     *     45 requests per minute which, if exceeded, results in
     *     an HTTP 429 exception (Too Many Requests).
     * 
     * @throws RequestException
     * @return Response – response object
     */
    private function fetchDataForIPFromAPI(): Response {
        $url = $this->getURL();

        return Http::get($url)->throw();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the URL of the API being used to detect the visitor's country.
     * 
     * NB! Keep in mind that the HTTPS protocol is available
     *     only for registered members, the HTTP one on the
     *     other hand is free.
     * 
     * @return string
     */
    private function getURL(): string {
        $fields = $this->getExpectedFieldsList();

        return "http://ip-api.com/json/{$this->ip}?fields={$fields}";
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all expected fields from the API as a string.
     * 
     * @return string
     */
    private function getExpectedFieldsList(): string {
        return implode(',', $this->getExpectedFields());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Determine which fields to expect from the API.
     * 
     * @see https://ip-api.com/docs/api:json – list of all supported fields
     * 
     * @return array<int,string>
     */
    private function getExpectedFields(): array {
        return [
            'countryCode',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Instead of storing all data fields fetched from the API
     * as individual class properties with the respective getter
     * methods, or exposing the response property to the outer
     * world, a single magic method is used which extracts the
     * relevant properties from the response.
     * For this to work, the user should use undefined getter
     * methods starting with the word "get", followed by the
     * of the property, e.g. getCountryCode(). 
     * If the method does not start with "get" or is trying
     * to retrieve a property wihch has not even been fetched
     * from the API, an exception will be thrown.
     * 
     * @throws BadMethodCallException – incorrect method name
     * @param  string $method    – name of the undefined method
     * @param  mixed  $arguments – arguments list, should be empty
     * @return mixed
     */
    public function __call(string $method, mixed $arguments) {
        $this->validateMethodIsGetter($method);

        $property = $this->extractPropertyFromGetterMethod($method);

        // if the API call was unsuccessful, the error will be logged,
        // and the getter methods will return NULL values from the response
        return $this->response->json($property);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Make sure the magic method being called via __call is a getter method.
     * 
     * @throws BadMethodCallException – method is not getter
     * @param  string $method         – name of the magic getter method
     * @return void
     */
    private function validateMethodIsGetter(string $method): void {
        if ( ! preg_match('/^get/', $method)) {
            throw new BadMethodCallException("'{$method}' is not a getter method.");
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Extract the name of the property from the magic getter method
     * and convert it to camel-case (since this is how they are
     * defined in the remote system).
     * If no property can be extracted or it's not in the expected
     * fields list, an exception will be thrown.
     * 
     * @throws BadMethodCallException – no/unexpected proprety name
     * @param  string $method         – name of the magic getter method
     * @return string
     */
    private function extractPropertyFromGetterMethod(string $method): string {
        $property = preg_replace('/^get/', '', $method);

        if ( ! Str::length($property)) {
            throw new BadMethodCallException("Getter method '{$method}' is missing a property.");
        }

        $camel = Str::camel($property);

        if ( ! in_array($camel, $this->getExpectedFields())) {
            throw new BadMethodCallException("Property '{$camel}' is not fetched from the API");
        }

        return $camel;
    }

}