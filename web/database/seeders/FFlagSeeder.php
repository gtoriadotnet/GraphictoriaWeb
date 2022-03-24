<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\FFlag;
use App\Models\Fbucket;

class FFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$appSettingsCommon = Fbucket::create([
			'name' => 'AppSettingsCommon',
			'protected' => true
		]);
		
        $clientAppSettings = Fbucket::create([
			'name' => 'ClientAppSettings',
			'inheritedGroupIds' => json_encode([ $appSettingsCommon->id ])
		]);
		
		$cloudAppSettings = Fbucket::create([
			'name' => 'CloudCompute',
			'protected' => true,
			'inheritedGroupIds' => json_encode([ $appSettingsCommon->id ])
		]);
		
		$clientSharedSettings = Fbucket::create([
			'name' => 'ClientSharedSettings'
		]);
		
		$arbiterAppSettings = Fbucket::create([
			'name' => 'Arbiter'
		]);
		
		$bootstrapperCommon = Fbucket::create([
			'name' => 'BootstrapperCommon',
			'protected' => true
		]);
		
		$windowsBootstrapperSettings = Fbucket::create([
			'name' => 'WindowsBootstrapperSettings',
			'inheritedGroupIds' => json_encode([ $bootstrapperCommon->id ])
		]);
		
		$windowsStudioBootstrapperSettings = Fbucket::create([
			'name' => 'WindowsStudioBootstrapperSettings',
			'inheritedGroupIds' => json_encode([ $bootstrapperCommon->id ])
		]);
    }
}
