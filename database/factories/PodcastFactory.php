<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'episode_number' => $this->faker->unique()->numberBetween(1, 200),
            'duration' => $this->faker->numberBetween(300, 3600),// من 5 دقائق إلى ساعة
        ];
    }
}
