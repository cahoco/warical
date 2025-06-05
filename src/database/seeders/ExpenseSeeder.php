<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\Profile;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $profile = DB::table('profiles')->first();

        $a_name = $profile->a_name ?? 'Aさん';
        $b_name = $profile->b_name ?? 'Bさん';

        Expense::create([
            'date' => now()->subDays(2)->format('Y-m-d'),
            'category' => '食費',
            'amount' => 3000,
            'payer' => $a_name, // ひかり
        ]);

        Expense::create([
            'date' => now()->subDays(1)->format('Y-m-d'),
            'category' => '交通費',
            'amount' => 2000,
            'payer' => $b_name, // けんた
        ]);
    }
}
