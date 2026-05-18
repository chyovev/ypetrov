<?php

namespace Tests\Feature\API;

use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use Tests\TestCase;

class SpamResponderTest extends TestCase
{

    use DatabaseTransactions;

    ///////////////////////////////////////////////////////////////////////////
    public function test_visitor_getting_banned(): void {
        $visitor = Visitor::factory()->create(['is_banned' => false]);
        $this->app->instance(Visitor::class, $visitor);

        $this->assertFalse($visitor->is_banned);

        Route::post('/test', fn() => 'test')->middleware(ProtectAgainstSpam::class);

        // populating the spam field
        $data = [
            config('heypot.name_field_name')         => 'Test',
            config('honeypot.valid_from_field_name') => Carbon::now(),
        ];

        $response = $this->post('/test', $data);
        $response->assertStatus(200);

        $this->assertTrue($visitor->is_banned);
    }

}