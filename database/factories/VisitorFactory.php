<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     * 
     * NB! The IP gets passed to a virtual 'ip' attribute
     *     whose mutator takes care of hashing it and passing
     *     it this way to the actual database field – ip_hash.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'ip'           => fake()->unique()->ipv6(),
            'country_code' => fake()->optional()->countryCode(),
        ];
    }

}
