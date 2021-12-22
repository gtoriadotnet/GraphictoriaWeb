<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\WebsiteConfiguration;

class WebConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebsiteConfiguration::create([
			'name' => 'MaintenancePassword',
			'value' => json_encode(
				[
					'combination' => [0,7,8,9,10,11],
					'password' => '@bs0lut3lyM@55!v3P@55w0rd'
				])
		]); // please please please please please please please change the default password
    }
}
