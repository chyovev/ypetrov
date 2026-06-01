<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Utils\IPLocator;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every page load should log the visitor's IP address to
 * the visitors table (after proper hashing which is taken
 * care of by a mutator).
 * From then on, the Visitor instance gets registered as
 * a service to be used later on when the actual request
 * gets processed.
 */

class RegisterVisitor
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private IPLocator $ipLocator) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    public function handle(Request $request, Closure $next): Response {
        $visitor = $this->getVisitor($request);

        $this->bindVisitor($visitor);

        return $next($request);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch a visitor by their IP address. If no record is found, create one.
     */
    private function getVisitor(Request $request): Visitor {
        $ip = $request->ip();

        try {
            return $this->fetchVisitor($ip);
        }
        catch (ModelNotFoundException $e) {
            return $this->createVisitor($ip);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch an existing visitor from the database and update its timestamps.
     * If no visitor is found, an exception will be thrown.
     * 
     * @throws ModelNotFoundException – no visitor record found
     */
    private function fetchVisitor(string $ip): Visitor {
        // hash the IP by applying inbound casting
        // before fetching a visitor from the database
        $ipHash  = (new Visitor(['ip_hash' => $ip]))->ip_hash;
        $visitor = Visitor::where('ip_hash', $ipHash)->firstOrFail();
        
        // if the visitor has no country code (initial registration
        // attepmt was unsuccessful), try to detect it now
        if (is_null($visitor->country_code)) {
            $visitor->updateQuietly(['country_code' => $this->getVisitorCountryCode($ip)]);
        }
        
        $visitor->updateLastVisitDate();
        
        return $visitor;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createVisitor(string $ip): Visitor {
        return Visitor::create([
            'ip_hash'      => $ip, // field has mutator
            'country_code' => $this->getVisitorCountryCode($ip),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to figure out the country code of the visitor
     * by passing their IP address to an IP locator API.
     * 
     * NB! Keep in mind that the API call might not be
     *     successful (e.g. too many requests in a single
     *     minute) which will result into an exception
     *     being thrown. If that happens, catch it, log it
     *     and move on without a country code.
     */
    private function getVisitorCountryCode(string $ip): ?string {
        try {
            return $this->ipLocator->locate($ip);
        }
        catch (Exception $e) {
            Log::error($e);

            return null;
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Register the Visitor instance as shared into the container.
     * This way, the object can easily be used across controllers
     * by dependency-injecting it inside the constructor.
     */
    private function bindVisitor(Visitor $visitor): void {
        app()->instance(Visitor::class, $visitor);

        // share the visitor also as a template variable
        view()->share('visitor', $visitor);
    }

}
