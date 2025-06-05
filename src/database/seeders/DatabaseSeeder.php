<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 実行順に注意：プロフィール→支出
        $this->call([
            ProfilesTableSeeder::class,
            ExpenseSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
