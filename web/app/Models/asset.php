<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssetVersion;

class Asset extends Model
{
    use HasFactory;
	
	public function user()
    {
        return $this->belongsTo(User::class, 'creatorId');
    }
	
	public function latestVersion()
	{
		return $this->belongsTo(AssetVersion::class, 'assetVersionId');
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
