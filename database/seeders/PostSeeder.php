<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{

    public function run()
    {
        Post::create([
            'title' => 'Leha post 1',
            'content' => 'My super cool post 1!',
            'status' => 'active',
            'user_id' => 1,
            'category_id' => 1
        ]);

        Post::create([
            'title' => 'Leha post 2',
            'content' => 'My super cool post 2!',
            'status' => 'active',
            'user_id' => 1,
            'category_id' => 2
        ]);

        Post::create([
            'title' => 'Zeka post 1',
            'content' => 'I am loh!',
            'status' => 'disactive',
            'user_id' => 2,
            'category_id' => 1
        ]);
        Post::create([
            'title' => 'Zeka post 2',
            'content' => 'I am lohovki loh!',
            'status' => 'active',
            'user_id' => 2,
            'category_id' => 1
        ]);

        Post::create([
            'title' => 'Vlad post 1',
            'content' => 'I am Vlad!',
            'status' => 'active',
            'user_id' => 3,
            'category_id' => 2
        ]);
        Post::create([
            'title' => 'Vlad post 2',
            'content' => 'I am Vlad 2))))))!',
            'status' => 'active',
            'user_id' => 3,
            'category_id' => 1
        ]);
    }
}
