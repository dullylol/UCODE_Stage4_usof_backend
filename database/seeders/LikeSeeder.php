<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;

class LikeSeeder extends Seeder
{

    public function run()
    {
        Like::create([
            'user_id' => 1,
            'post_id' => 1,
            'comment_id' => null,
            'type' => 'like'
        ]);

        Like::create([
            'user_id' => 1,
            'post_id' => 2,
            'comment_id' => null,
            'type' => 'like'
        ]);

        Like::create([
            'user_id' => 2,
            'post_id' => 1,
            'comment_id' => null,
            'type' => 'dislike'
        ]);

        Like::create([
            'user_id' => 2,
            'post_id' => 1,
            'comment_id' => null,
            'type' => 'dislike'
        ]);

        Like::create([
            'user_id' => 2,
            'post_id' => 1,
            'comment_id' => null,
            'type' => 'like'
        ]);

        Like::create([
            'user_id' => 2,
            'post_id' => null,
            'comment_id' => 1,
            'type' => 'like'
        ]);
    }
}
