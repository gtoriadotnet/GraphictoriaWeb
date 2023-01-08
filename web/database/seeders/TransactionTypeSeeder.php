<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TransactionType;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::create(['name' => 'Purchases', 'format' => 'Purchased ']);
        TransactionType::create(['name' => 'Sales', 'format' => 'Sold ']);
        TransactionType::create(['name' => 'Commissions', 'format' => 'Sold ']); // third party sales
        TransactionType::create(['name' => 'Group Payouts', 'format' => '']);
        TransactionType::create(['name' => 'Admin Adjustment', 'format' => 'Tokens adjusted by administrator.', 'hidden' => true]);
    }
}
