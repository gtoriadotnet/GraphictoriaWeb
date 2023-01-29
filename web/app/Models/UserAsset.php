<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'owner_id',
		'asset_id',
		'serial'
	];
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
	
	public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
	
	public static function createSerialed($ownerId, $assetId)
	{
		$userAsset = self::create([
			'owner_id' => $ownerId,
			'asset_id' => $assetId,
			'serial' => self::where('asset_id', $assetId)->count()+1
		]);
		$userAsset->postSerialize();
		
		return $userAsset;
	}
	
	public function postSerialize()
	{
		// Grant package assets.
		if($this->asset->assetType->id == 32)
		{
			foreach(explode(';', $this->asset->getPackageAssetIds()) as $asset)
			{
				if($this->owner->hasAsset($asset)) continue;
				
				self::createSerialed($this->owner_id, $asset);
			}
		}
	}
}
