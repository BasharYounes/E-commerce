<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PodcastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        Podcast::factory(20)->create()->each(function ($podcast) use ($categories) {
            $podcast->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
        });
    }
}
