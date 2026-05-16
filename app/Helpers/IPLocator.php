<?php

namespace App\Helpers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class IPLocator
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Locate an IP address returning a country code.
     * 
     * NB! Only HTTP available on free API
     * 
     * @throws RequestException
     */
    public function locate(string $ip): ?string {
        $url      = "http://ip-api.com/json/{$ip}?fields=countryCode";
        $response = Http::get($url)->throw();

        return $response->json('countryCode');
    }

}