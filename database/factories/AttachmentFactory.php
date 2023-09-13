<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array {
        return [
            'original_file_name' => $this->generateFakeFileName(),
            'server_file_name'   => fake()->unique()->word(),
            'caption'            => fake()->optional()->sentence(),
            'file_size'          => fake()->numberBetween(100, 100000),
            'mime_type'          => fake()->mimeType(),
            'order'              => fake()->randomNumber(1),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A fake file name consists of three random words
     * and a random extension.
     * 
     * NB! Keep in mind that during tests the extension
     *     and the mime type will not necessarily align.
     * 
     * @return string
     */
    private function generateFakeFileName(): string {
        $name      = fake()->words(3, true);
        $extension = fake()->fileExtension();

        return "{$name}.{$extension}";
    }
}
