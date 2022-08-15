<?php

/*
	Graphictoria 2022
	CDN helper.
*/

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\CdnHash;

class CdnHelper
{
	public static function GetDisk()
	{
		return Storage::build([
			'driver' => 'local',
			'root' => storage_path('app/content'),
		]);
	}
	
	public static function Hash($content)
	{
		return hash('sha256', $content);
	}
	
	public static function SaveContent($content, $mime)
	{
		$disk = self::GetDisk();
		$hash = self::Hash($content);
		
		if(!$disk->exists($hash) || !CdnHash::where('hash', $hash)->exists()) {
			$disk->put($hash, $content);
			
			$cdnItem = new CdnHash();
			$cdnItem->hash = $hash;
			$cdnItem->mime_type = $mime;
			$cdnItem->save();
		}
		
		return $hash;
	}
	
	public static function SaveContentB64($contentB64, $mime)
	{
		return self::SaveContent(base64_decode($contentB64), $mime);
	}
}
