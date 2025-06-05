<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $defaults = ['食費', '交通費', '日用品', '娯楽', '交際費'];

        foreach ($defaults as $index => $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1] // 並び順（1からスタート）
            );
        }
    }
}
