<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expense::create([
            'date' => now()->subDays(2),
            'category' => '食費',
            'amount' => 3000,
            'payer' => 'Aさん',
        ]);

        Expense::create([
            'date' => now()->subDays(1),
            'category' => '交通費',
            'amount' => 2000,
            'payer' => 'Bさん',
        ]);
    }
}
