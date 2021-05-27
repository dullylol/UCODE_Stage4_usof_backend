<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentSeeder extends Seeder
{

    public function run()
    {
        Comment::create([
            'user_id' => 1,
            'post_id' => 2,
            'content' => "Hello from Leha"
        ]);
        Comment::create([
            'user_id' => 2,
            'post_id' => 2,
            'content' => "Hello from Zeka"
        ]);
        Comment::create([
            'user_id' => 3,
            'post_id' => 2,
            'content' => "Hello from Vlad"
        ]);
    }
}
