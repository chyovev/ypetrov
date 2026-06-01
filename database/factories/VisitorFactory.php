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
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'ip_hash'      => fake()->unique()->ipv6(), // field has mutator
            'country_code' => fake()->optional()->countryCode(),
            'is_banned'    => fake()->boolean(99),
        ];
    }

}
