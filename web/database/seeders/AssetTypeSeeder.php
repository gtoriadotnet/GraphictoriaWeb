<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\AssetType;

class AssetTypeSeeder extends Seeder
{
	/* Default asset types */
	protected $assetTypes = [
		[
			'name' => 'Product',
			'locked' => true
		],
		[
			'name' => 'Image',
			'renderable' => true,
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'T-Shirt',
			'renderable' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Audio',
			'copyable' => true
		],
		[
			'name' => 'Mesh',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'Lua',
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'HTML',
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'Text',
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'Hat',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Place',
			'renderable' => true
		],
		[
			'name' => 'Model',
			'renderable' => true
		],
		[
			'name' => 'Shirt',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Pants',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Decal',
			'renderable' => true,
			'copyable' => true
		],
		null,
		null,
		[
			'name' => 'Avatar',
			'locked' => true
		],
		[
			'name' => 'Head',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Face',
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Gear',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		null,
		[
			'name' => 'Badge',
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'Group Emblem',
			'renderable' => true,
			'copyable' => true,
			'locked' => true
		],
		null,
		[
			'name' => 'Animation',
			'copyable' => true
		],
		[
			'name' => 'Arms',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Legs',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Torso',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Right Arm',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Left Arm',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Left Leg',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Right Leg',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true,
			'wearable' => true
		],
		[
			'name' => 'Package',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'YouTubeVideo',
			'locked' => true
		],
		[
			'name' => 'Game Pass',
			'sellable' => true
		],
		[
			'name' => 'App',
			'locked' => true
		],
		null,
		[
			'name' => 'Code',
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'Plugin',
			'sellable' => true
		],
		[
			'name' => 'SolidModel',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'locked' => true
		],
		[
			'name' => 'MeshPart',
			'renderable' => true,
			'renderable3d' => true
		],
		[
			'name' => 'Hair Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Face Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Neck Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Shoulder Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Front Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Back Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Waist Accessory',
			'renderable' => true,
			'renderable3d' => true,
			'copyable' => true,
			'sellable' => true,
			'wearable' => true
		],
		[
			'name' => 'Climb Animation',
			'locked' => true
		],
		[
			'name' => 'Death Animation',
			'locked' => true
		],
		[
			'name' => 'Fall Animation',
			'locked' => true
		],
		[
			'name' => 'Idle Animation',
			'locked' => true
		],
		[
			'name' => 'Jump Animation',
			'locked' => true
		],
		[
			'name' => 'Run Animation',
			'locked' => true
		],
		[
			'name' => 'Swim Animation',
			'locked' => true
		],
		[
			'name' => 'Walk Animation',
			'locked' => true
		],
		[
			'name' => 'Pose Animation',
			'locked' => true
		],
		[
			'name' => 'Ear Accessory',
			'locked' => true
		],
		[
			'name' => 'Eye Accessory',
			'locked' => true
		],
		[
			'name' => 'LocalizationTableManifest',
			'locked' => true
		],
		[
			'name' => 'LocalizationTableTranslation',
			'locked' => true
		],
		[
			'name' => 'Emote Animation',
			'locked' => true
		],
		[
			'name' => 'Video',
			'locked' => true
		],
		[
			'name' => 'TexturePack',
			'locked' => true
		],
		[
			'name' => 'T-Shirt Accessory',
			'locked' => true
		],
		[
			'name' => 'Shirt Accessory',
			'locked' => true
		],
		[
			'name' => 'Pants Accessory',
			'locked' => true
		],
		[
			'name' => 'Jacket Accessory',
			'locked' => true
		],
		[
			'name' => 'Sweater Accessory',
			'locked' => true
		],
		[
			'name' => 'Shorts Accessory',
			'locked' => true
		],
		[
			'name' => 'Left Shoe Accessory',
			'locked' => true
		],
		[
			'name' => 'Right Shoe Accessory',
			'locked' => true
		],
		[
			'name' => 'Dress Skirt Accessory',
			'locked' => true
		],
		[
			'name' => 'Font Family',
			'locked' => true
		],
		[
			'name' => 'Font Face',
			'locked' => true
		],
		[
			'name' => 'MeshHiddenSurfaceRemoval',
			'locked' => true
		],
		[
			'name' => 'Eyebrow Accessory',
			'locked' => true
		],
		[
			'name' => 'Eyelash Accessory',
			'locked' => true
		],
		[
			'name' => 'Mood Animation',
			'locked' => true
		],
		[
			'name' => 'Dynamic Head',
			'locked' => true
		],
		[
			'name' => 'CodeSnippet',
			'locked' => true
		]
	];
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->assetTypes as $typeId => $assetType) {
			AssetType::create($assetType != null ? array_merge(['id' => $typeId], $assetType) : ['name'=>null]);
		}
    }
}
