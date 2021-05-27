<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{

    public function run()
    {
        Category::create([
            'title' => 'Games',
            'description' => 'About games'
        ]);

        Category::create([
            'title' => 'Govno',
            'description' => 'About govno'
        ]);

        Category::create([
            'title' => 'Else',
            'description' => 'About something else...'
        ]);
    }
}
