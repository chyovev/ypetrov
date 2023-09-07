<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
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
            'is_active'    => fake()->boolean(90),
            'title'        => $title,
            'slug'         => $slug,
            'publish_date' => fake()->optional()->date(),
            'summary'      => fake()->sentences(2, true),
            'order'        => fake()->randomNumber(1),
        ];
    }
}
