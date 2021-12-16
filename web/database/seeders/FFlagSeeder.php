<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FFlag;
use App\Models\Fastgroup;

class FFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$appSettingsCommon = Fastgroup::create([
			'name' => 'AppSettingsCommon',
			'protected' => true
		]);
		
        $clientAppSettings = Fastgroup::create([
			'name' => 'ClientAppSettings',
			'inheritedGroupIds' => json_encode([ $appSettingsCommon->id ])
		]);
		
		$cloudAppSettings = Fastgroup::create([
			'name' => 'CloudCompute',
			'protected' => true,
			'inheritedGroupIds' => json_encode([ $appSettingsCommon->id ])
		]);
		
		$clientSharedSettings = Fastgroup::create([
			'name' => 'ClientSharedSettings'
		]);
		
		$arbiterAppSettings = Fastgroup::create([
			'name' => 'Arbiter'
		]);
		
		$bootstrapperCommon = Fastgroup::create([
			'name' => 'BootstrapperCommon',
			'protected' => true
		]);
		
		$windowsBootstrapperSettings = Fastgroup::create([
			'name' => 'WindowsBootstrapperSettings',
			'inheritedGroupIds' => json_encode([ $bootstrapperCommon->id ])
		]);
		
		$windowsStudioBootstrapperSettings = Fastgroup::create([
			'name' => 'WindowsStudioBootstrapperSettings',
			'inheritedGroupIds' => json_encode([ $bootstrapperCommon->id ])
		]);
    }
}
