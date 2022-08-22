<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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
	
	public function parentAsset()
    {
        return $this->belongsTo(User::class, 'parentAssetId');
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
		$renderId = $this->id;
		
		// TODO: XlXi: Turn this into a switch case and fill in the rest of the unrenderables.
		// 			   Things like HTML assets should just have a generic "default" image.
		if($this->assetTypeId == 1) // Image
			$renderId = $this->parentAsset->id;
		
		$thumbnail = Http::get(route('thumbnails.v1.asset', ['id' => $renderId, 'type' => '2d']));
		if($thumbnail->json('status') == 'loading')
			return 'https://gtoria.local/images/busy/asset.png';
		
		return $thumbnail->json('data');
	}
	
	public function set2DHash($hash)
	{
		$this->thumbnail2DHash = $hash;
		$this->timestamps = false;
		$this->save();
	}
	
	public function set3DHash($hash)
	{
		$this->thumbnail3DHash = $hash;
		$this->timestamps = false;
		$this->save();
	}
	
	public function getCreated()
	{
		$date = $this['created_at'];
		if(Carbon::now()->greaterThan($date->copy()->addDays(2)))
			$date = $date->isoFormat('lll');
		else
			$date = $date->calendar();
		
		return $date;
	}
	
	public function getUpdated()
	{
		$date = $this['updated_at'];
		if(Carbon::now()->greaterThan($date->copy()->addDays(2)))
			$date = $date->isoFormat('lll');
		else
			$date = $date->calendar();
		
		return $date;
	}
	
	// Version 0 is internally considered the latest.
	public function getContent($version = 0)
	{
		if($version == 0)
			return $this->latestVersion->contentURL;
		
		$assetVersion = AssetVersion::where('parentAsset', $this->id)
									->where('localVersion', $version)
									->first();
		
		return ($assetVersion ? $assetVersion->contentURL : null);
	}
}
