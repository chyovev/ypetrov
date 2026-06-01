<?php

namespace Tests\Feature\Casts;

use App\Casts\Sha256Cast;
use App\Models\Visitor;
use Tests\TestCase;

class Sha256CastTest extends TestCase
{

    ///////////////////////////////////////////////////////////////////////////
    public function test_hashed_value(): void {
        $hash  = (new Sha256Cast)->set(new Visitor, 'key', 'value', []);

        $this->assertSame('cd42404d52ad55ccfa9aca4adc828aa5800ad9d385a0671fbcbf724118320619', $hash);
    }
}