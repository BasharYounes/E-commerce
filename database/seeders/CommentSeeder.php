<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Podcast::all()->each(function ($podcast) use ($users) {
            for ($i = 0; $i < rand(2, 8); $i++) {
                Comment::factory()->create([
                    'commentable_id' => $podcast->id,
                    'commentable_type' => Podcast::class,
                    'user_id' => $users->random()->id,
                ]);
            }
        });
    }
}
