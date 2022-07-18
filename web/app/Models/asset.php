<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssetVersion;
use App\Models\User;

class Asset extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
		'updated_at' => 'datetime',
    ];
	
	protected $assetTypes = [
		/* 0 */  'Product',
		/* 1 */  'Image',
		/* 2 */  'T-Shirt',
		/* 3 */  'Audio',
		/* 4 */  'Mesh',
		/* 5 */  'Lua',
		/* 6 */  'HTML',
		/* 7 */  'Text',
		/* 8 */  'Hat',
		/* 9 */  'Place',
		/* 10 */ 'Model',
		/* 11 */ 'Shirt',
		/* 12 */ 'Pants',
		/* 13 */ 'Decal',
		/* 14 */ null, // Doesn't exist on Roblox.
		/* 15 */ null, // Doesn't exist on Roblox.
		/* 16 */ 'Avatar',
		/* 17 */ 'Head',
		/* 18 */ 'Face',
		/* 19 */ 'Gear',
		/* 20 */ null, // Doesn't exist on Roblox.
		/* 21 */ 'Badge',
		/* 22 */ 'Group Emblem',
		/* 23 */ null, // Doesn't exist on Roblox.
		/* 24 */ 'Animation',
		/* 25 */ 'Arms',
		/* 26 */ 'Legs',
		/* 27 */ 'Torso',
		/* 28 */ 'Right Arm',
		/* 29 */ 'Left Arm',
		/* 30 */ 'Left Leg',
		/* 31 */ 'Right Leg',
		/* 32 */ 'Package',
		/* 33 */ 'YouTubeVideo',
		/* 34 */ 'Game Pass',
		/* 35 */ 'App',
		/* 36 */ null, // Doesn't exist on Roblox.
		/* 37 */ 'Code',
		/* 38 */ 'Plugin',
		/* 39 */ 'SolidModel',
		/* 40 */ 'MeshPart',
		/* 41 */ 'Hair Accessory',
		/* 42 */ 'Face Accessory',
		/* 43 */ 'Neck Accessory',
		/* 44 */ 'Shoulder Accessory',
		/* 45 */ 'Front Accessory',
		/* 46 */ 'Back Accessory',
		/* 47 */ 'Waist Accessory',
		/* 48 */ 'Climb Animation',
		/* 49 */ 'Death Animation',
		/* 50 */ 'Fall Animation',
		/* 51 */ 'Idle Animation',
		/* 52 */ 'Jump Animation',
		/* 53 */ 'Run Animation',
		/* 54 */ 'Swim Animation',
		/* 55 */ 'Walk Animation',
		/* 56 */ 'Pose Animation',
		/* 57 */ 'Ear Accessory',
		/* 58 */ 'Eye Accessory',
		/* 59 */ 'LocalizationTableManifest',
		/* 60 */ 'LocalizationTableTranslation',
		/* 61 */ 'Emote Animation',
		/* 62 */ 'Video',
		/* 63 */ 'TexturePack',
		/* 64 */ 'T-Shirt Accessory',
		/* 65 */ 'Shirt Accessory',
		/* 66 */ 'Pants Accessory',
		/* 67 */ 'Jacket Accessory',
		/* 68 */ 'Sweater Accessory',
		/* 69 */ 'Shorts Accessory',
		/* 70 */ 'Left Shoe Accessory',
		/* 71 */ 'Right Shoe Accessory',
		/* 72 */ 'Dress Skirt Accessory',
		/* 73 */ 'Font Family',
		/* 74 */ 'Font Face',
		/* 75 */ 'MeshHiddenSurfaceRemoval'
	];
	
	protected $assetGenres = [
		/* 0 */ 'All',
		/* 1 */ 'Town And City',
		/* 2 */ 'Medieval',
		/* 3 */ 'Sci-Fi',
		/* 4 */ 'Fighting',
		/* 5 */ 'Horror',
		/* 6 */ 'Naval',
		/* 7 */ 'Adventure',
		/* 8 */ 'Sports',
		/* 9 */ 'Comedy',
		/* 10 */ 'Western',
		/* 11 */ 'Military',
		/* 12 */ 'Skate Park',
		/* 13 */ 'Building',
		/* 14 */ 'FPS',
		/* 15 */ 'RPG'
	];
	
	protected $gearAssetGenres = [
		/* 0 */ 'Melee Weapon',
		/* 1 */ 'Ranged Weapon',
		/* 2 */ 'Explosive',
		/* 3 */ 'Power Up',
		/* 4 */ 'Navigation Enhancer',
		/* 5 */ 'Musical Instrument',
		/* 6 */ 'Social Item',
		/* 7 */ 'Building Tool',
		/* 8 */ 'Personal Transport'
	];
	
	public function user()
    {
        return $this->belongsTo(User::class, 'creatorId');
    }
	
	public function typeString()
	{
		return $this->assetTypes[$this->assetTypeId];
	}
	
	public function latestVersion()
	{
		return $this->belongsTo(AssetVersion::class, 'assetVersionId');
	}
	
	public function getThumbnail()
	{
		return 'https://gtoria.local/images/testing/hat.png';
	}
	
	// Version 0 is internally considered the latest.
	public function getContent($version = 0)
	{
		if($version === 0)
			return $this->latestVersion()->contentURL;
		
		$assetVersion = AssetVersion::where('parentAsset', $this->id)
									->where('localVersion', $version)
									->get();
		
		return ($assetVersion !== null ? $assetVersion->contentURL : null);
	}
}
