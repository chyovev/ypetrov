<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Database\Factories\Traits\HasActiveState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Poem>
 */
class PoemFactory extends Factory
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
            'is_active'          => fake()->boolean(90),
            'title'              => $title,
            'slug'               => $slug,
            'dedication'         => fake()->optional()->name(),
            'text'               => $this->fakePoem(),
            'use_monospace_font' => fake()->boolean(80),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fake a poem by having multiple sentences on new lines.
     * 
     * @return string
     */
    private function fakePoem(): string {
        $paragraphs        = rand(3, 4);
        $linesPerParagraph = 4;
        $poem              = '';

        for ($i = 1; $i <= $paragraphs; $i++) {
            for ($j = 1; $j <= $linesPerParagraph; $j++) {
                $poem .= fake()->sentence() . "\n";
            }

            $poem .= "\n";
        }

        return $poem;
    }
}
