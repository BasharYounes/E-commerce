<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'file_path' => $this->faker->url, // تمثيل لمسار ملف صوتي
            'cover_image' => $this->faker->imageUrl(640, 480, 'cover'),
            'published_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
