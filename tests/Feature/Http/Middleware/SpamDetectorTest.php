<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Spatie\Honeypot\ProtectAgainstSpam;
use Tests\TestCase;

class SpamDetectorTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_getting_banned(): void {
        $this->declareRoute();

        $visitor = $this->createVisitor();

        $this->assertFalse($visitor->is_banned);

        $response = $this->sendRequest(['phone' => 'Test']);
        $response->assertStatus(403);

        $this->assertTrue($visitor->is_banned);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_not_getting_banned_field_not_filled(): void {
        $this->declareRoute();

        $visitor = $this->createVisitor();

        $this->assertFalse($visitor->is_banned);

        $response = $this->sendRequest(['phone' => null]);
        $response->assertStatus(200);

        $this->assertFalse($visitor->is_banned);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_not_getting_banned_field_not_present(): void {
        $this->declareRoute();

        $visitor = $this->createVisitor();

        $this->assertFalse($visitor->is_banned);

        $response = $this->sendRequest([]);
        $response->assertStatus(200);

        $this->assertFalse($visitor->is_banned);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Declare a temp api route utilizing the middleware
     */
    private function declareRoute(): void {
        Route::post('/test', fn(): string => 'test')
            ->name('api.test')
            ->middleware("anti-spam:phone");
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createVisitor(): Visitor {
        $visitor = Visitor::factory()->create(['is_banned' => false]);

        // set as instance in order to be DI in the middleware
        $this->app->instance(Visitor::class, $visitor);

        return $visitor;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function sendRequest(array $data): TestResponse {
        return $this->post('/test', $data);
    }

}