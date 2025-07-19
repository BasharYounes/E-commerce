<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $comments = Comment::whereNull('parent_id')->get();     

        foreach ($comments as $comment) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Comment::factory()
                    ->reply($comment->id)
                    ->create([
                        'user_id' => $users->random()->id,
                        'commentable_id' => $comment->commentable_id,
                        'commentable_type' => $comment->commentable_type,
                    ]);
            }
        }
    }
}
