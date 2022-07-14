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
	
	public function user()
    {
        return $this->belongsTo(User::class, 'creatorId');
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
