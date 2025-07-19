<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Content;
use App\Models\Podcast;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = Channel::all();

        Podcast::all()->each(function ($podcast) use ($channels) {
            Content::factory()->create([
                'channel_id' => $channels->random()->id,
                'contentable_id' => $podcast->id,
                'contentable_type' => Podcast::class,
            ]);
        });
    }
}
