<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use App\Helpers\CdnHelper;
use App\Models\AssetType;

/* 
TODO: XlXi: game performance priority system
			where games can be chosen to have a higher
			thread count on RCC.

TODO: XlXi: game reccomendations, split words of title
			SELECT articleID, COUNT(keyword) FROM keyword WHERE keyword IN (A, B, C) GROUP BY articleID ORDER BY COUNT(keyword) DESC
*/

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
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'creatorId',
		'name',
		'description',
		'renderId',
		'approved',
		'priceInTokens',
		'onSale',
		'assetTypeId',
		'assetVersionId',
		'universeId',
		'maxPlayers',
		'chatStyleEnum'
	];
	
	/* TODO: XlXi: move to db */
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
	
	/* TODO: XlXi: move to db */
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
	
	public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'assetTypeId');
    }
	
	public function user()
    {
        return $this->belongsTo(User::class, 'creatorId');
    }
	
	public function parentAsset()
    {
        return $this->belongsTo(Asset::class, 'parentAssetId');
    }
	
	public function universe()
    {
        return $this->belongsTo(Universe::class, 'universeId');
    }
	
	public function latestVersion()
	{
		return $this->belongsTo(AssetVersion::class, 'assetVersionId');
	}
	
	public function typeString()
	{
		return $this->assetType->name;
	}
	
	public function isWearable()
	{
		return $this->assetType->wearable;
	}
	
	public function isRenderable()
	{
		return $this->assetType->renderable;
	}
	
	public function canRender3D()
	{
		return $this->assetType->renderable3d;
	}
	
	// XlXi: copyable() is when an asset is freely available for download
	//		 on /asset regardless of whether or not its on sale or owned.
	public function copyable()
	{
		return $this->assetType->copyable;
	}
	
	public function getThumbnail()
	{
		if($this->renderId)
		{
			$asset = Asset::where('id', $this->renderId)->first();
			return $asset->getThumbnail();
		}
		
		if($this->moderated)
			return '/thumbs/DeletedThumbnail.png';
		
		if(!$this->approved)
			return '/thumbs/PendingThumbnail.png';
		
		if(!$this->assetType->renderable)
			return '/thumbs/UnavailableThumbnail.png';
		
		if(!$this->thumbnail2DHash)
		{
			$thumbnail = Http::get(route('thumbnails.v1.asset', ['id' => $this->id, 'type' => '2d']));
			if($thumbnail->json('status') == 'loading')
				return ($this->assetTypeId == 9 ? '/images/busy/game.png' : '/images/busy/asset.png');
			
			return $thumbnail->json('data');
		}
		
		return route('content', $this->thumbnail2DHash);
	}
	
	// XlXi: For packages. https://abc.com/asset?id=1;https://abc.com/asset?id=2
	public function getPackageAssetUrls()
	{
		$result = '';
		$assets = $this->getPackageAssetIds();
		if(!$assets)
			return $result;
		
		foreach(explode(';', $assets) as $key=>$asset)
		{
			$result .= ($key > 0 ? ';' : '') . route('client.asset', ['id' => $asset]);
		}
		
		return $result;
	}
	
	public function getPackageAssetIds()
	{
		$disk = CdnHelper::GetDisk();
		
		return $disk->get($this->getContent());
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
	
	public function getShopUrl()
	{
		return route('shop.asset', ['asset' => $this, 'assetName' => Str::slug($this->name, '-')]);
	}
	
	public function logAdminUpload($uploaderId)
	{
		return AdminUpload::create([
			'asset_id' => $this->id,
			'uploader_id' => $uploaderId
		]);
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
