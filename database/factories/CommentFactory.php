<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'name'    => fake()->name(),
            'message' => fake()->paragraph(2),
            'ip_hash' => fake()->ipv6(), // class mutator takes care of hashing
        ];
    }
}
