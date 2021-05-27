<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    //php artisan migrate:refresh
    //php artisan make:seeder $class$Seeder
    //php artisan migrate:refresh --seed
    public function run()
    {
       $this->call(UserSeeder::class);
       $this->call(CategorySeeder::class);
       $this->call(PostSeeder::class);
       $this->call(CommentSeeder::class);
       $this->call(LikeSeeder::class);
    }
}
