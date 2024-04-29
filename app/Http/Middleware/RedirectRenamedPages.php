<?php

namespace App\Http\Middleware;

use File;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Since this is an ongoing project, there have been
 * several URL breaking changes over the years, so all
 * relevant obsolete addresses are mapped to their new
 * locations in the /storage/redirects.txt file
 * (separated by a white space).
 * From then on, this global middleware tries to match
 * the current URL to a potential address and respectively
 * redirects the user to it.
 */

class RedirectRenamedPages
{

    ///////////////////////////////////////////////////////////////////////////
    public function handle(Request $request, Closure $next): Response {
        $newAddress = $this->findPageNewAddress($request);

        if ( ! is_null($newAddress)) {
            return redirect($newAddress);
        }
        

        return $next($request);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if the current URL corresponds with a new address
     * which the user should eventually be redirected to.
     * 
     * @param  Request $request
     * @return string|null
     */
    private function findPageNewAddress(Request $request): ?string {
        $current   = urldecode($request->getPathInfo());
        $addresses = $this->getAllAddressMappings();

        foreach ($addresses as $item) {
            list ($old, $new) = $this->mapOldToNew($item);

            if ($old === $current) {
                return $new;
            }
        }

        return null;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Return all address mappings from the redirects.txt
     * file as a one-dimensional array.
     * 
     * @return string[]
     */
    private function getAllAddressMappings(): array {
        $filePath     = storage_path('redirects.txt');
        $fileContents = File::get($filePath);
        
        return preg_split('/\n\r?/', $fileContents);        
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Map the old address to a new one.
     * 
     * @param  string   $address – should be separated by a white space
     * @return string[] [old, new]
     */
    private function mapOldToNew(string $address): array {
        $parts = preg_split('/\s+/', $address, 2);

        array_walk($parts, function(&$value) {
            $value = trim($value);
        });

        return $parts;
    }
}
