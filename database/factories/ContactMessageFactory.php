<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'is_read' => fake()->boolean(10),
            'name'    => fake()->name(),
            'email'   => fake()->email(),
            'message' => fake()->paragraph(),
            'ip_hash' => fake()->ipv6(), // class mutator takes care of hashing
        ];
    }
}
