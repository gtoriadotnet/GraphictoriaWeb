<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\WebStatusSeeder;

use Database\Seeders\WebConfigurationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call([
			WebStatusSeeder::class,
			FFlagSeeder::class,
			WebConfigurationSeeder::class
		]);
    }
}
