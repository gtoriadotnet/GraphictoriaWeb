<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\UsageCounter;

class UsageCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsageCounter::create([
			'name' => 'CPU',
			'value' => '1'
		]);
		
		UsageCounter::create([
			'name' => 'Memory',
			'value' => '{"MemTotal":32768,"MemFree":1}'
		]);
    }
}
