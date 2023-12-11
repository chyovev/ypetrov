<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaticPage>
 */
class StaticPageFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'title' => fake()->unique()->sentence(3),
            'text'  => "<div>" . fake()->paragraph(10) . "</div>",
        ];
    }
}
