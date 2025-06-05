<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = ['食費', '交通費', '日用品', '娯楽', '交際費'];

        foreach ($defaults as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
