<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Database\Factories\Traits\HasActiveState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{

    /**
     * Add active() and inactive() state
     * methods to factory.
     */
    use HasActiveState;

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
            'publisher'    => fake()->company(),
            'publish_year' => fake()->year(),
            'order'        => fake()->randomNumber(1),
        ];
    }

}
