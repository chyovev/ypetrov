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
     * NB! Class mutator takes care of hashing the IP.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'ip_hash'      => fake()->unique()->ipv6(),
            'country_code' => fake()->optional()->countryCode(),
        ];
    }

}
