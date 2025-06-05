<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('profiles')->insert([
            'a_name' => 'ひかり',
            'a_birthday' => '1995-06-01',
            'a_disliked_foods' => 'トマト',
            'b_name' => 'けんた',
            'b_birthday' => '1994-08-15',
            'b_disliked_foods' => 'ピーマン',
            'anniversary' => '2020-04-20',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
