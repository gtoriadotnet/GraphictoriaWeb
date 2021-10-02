<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\WebStatus;

class WebStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebStatus::create([
			'name' => 'ThumbArbiter'
		]);
		
		WebStatus::create([
			'name' => 'GamesArbiter'
		]);
    }
}
