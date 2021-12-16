<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FFlag;
use App\Models\FastGroup;

class FFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientAppSettings = FastGroup::create([
			'name' => 'ClientAppSettings'
		]);
		
		$cloudAppSettings = FastGroup::create([
			'name' => 'CloudCompute'
		]);
		
		$clientSharedSettings = FastGroup::create([
			'name' => 'ClientSharedSettings'
		]);
		
		$arbiterAppSettings = FastGroup::create([
			'name' => 'Arbiter'
		]);
		
		$windowsBootstrapperSettings = FastGroup::create([
			'name' => 'WindowsBootstrapperSettings'
		]);
		
		$windowsStudioBootstrapperSettings = FastGroup::create([
			'name' => 'WindowsStudioBootstrapperSettings'
		]);
    }
}
