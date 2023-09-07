<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Essay>
 */
class EssayFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        $title = fake()->unique()->sentence(3);
        $slug  = Str::slug($title);

        return [
            'is_active' => fake()->boolean(90),
            'title'     => $title,
            'slug'      => $slug,
            'text'      => fake()->paragraph(10),
            'order'     => fake()->randomNumber(1),
        ];
    }
}
