<?php

/*
	XlXi 2022
	Asset Helper
*/

namespace App\Helpers;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Helpers\CdnHelper;
use App\Models\Asset;
use App\Models\AssetVersion;
use App\Models\RobloxAsset;

class AssetHelper
{
	private static function cookiedRequest()
	{
		$cookieJar = CookieJar::fromArray([
			'.ROBLOXSECURITY' => env('app.robloxcookie')
		], '.roblox.com');
		
		return Http::withOptions(['cookies' => $cookieJar, 'headers' => ['User-Agent' => 'Roblox/WinInet']]);
	}
	
	public static function uploadRobloxAsset($id, $uploadToHolder = false)
	{
		{
			$uploadedAsset = RobloxAsset::where('robloxAssetId', $id)->first();
			if($uploadedAsset)
				return $uploadedAsset->asset;
		}
		
		$marketplaceResult = self::cookiedRequest()->get('https://api.roblox.com/marketplace/productinfo?assetId=' . $id);
		$assetResult = self::cookiedRequest()->get('https://assetdelivery.roblox.com/v2/asset?id=' . $id);
		
		if(!$marketplaceResult->ok() || !$assetResult->ok())
			return false;
		
		$assetTypeId = $marketplaceResult['AssetTypeId'];
		if(
			$assetTypeId == 41 || // Hair Accessory
			$assetTypeId == 42 || // Face Accessory
			$assetTypeId == 43 || // Neck Accessory
			$assetTypeId == 44 || // Shoulder Accessory
			$assetTypeId == 45 || // Front Accessory
			$assetTypeId == 46 || // Back Accessory
			$assetTypeId == 47    // Waist Accessory
		) {
			$assetTypeId = 8;
		}
		
		$assetContent = Http::get($assetResult['locations'][0]['location']);
		$hash = CdnHelper::SaveContent($assetContent->body(), $assetContent->header('Content-Type'));
		$asset = Asset::create([
			'creatorId' => ($uploadToHolder ? 1 : Auth::user()->id),
			'name' => $marketplaceResult['Name'],
			'description' => $marketplaceResult['Description'],
			'approved' => true,
			'priceInTokens' => $marketplaceResult['PriceInRobux'] ?: 0,
			'onSale' => $marketplaceResult['IsForSale'],
			'assetTypeId' => $assetTypeId,
			'assetVersionId' => 0
		]);
		$assetVersion = AssetVersion::create([
			'parentAsset' => $asset->id,
			'localVersion' => 1,
			'contentURL' => $hash
		]);
		$asset->assetVersionId = $assetVersion->id;
		$asset->save();
		
		RobloxAsset::create([
			'robloxAssetId' => $id,
			'localAssetId' => $asset->id
		]);
		
		return $asset;
	}
	
	public static function uploadCustomRobloxAsset($id, $uploadToHolder = false, $b64Content)
	{
		{
			$uploadedAsset = RobloxAsset::where('robloxAssetId', $id)->first();
			if($uploadedAsset)
				return $uploadedAsset->asset;
		}
		
		$marketplaceResult = self::cookiedRequest()->get('https://api.roblox.com/marketplace/productinfo?assetId=' . $id);
		
		if(!$marketplaceResult->ok())
			return false;
		
		$assetTypeId = $marketplaceResult['AssetTypeId'];
		if(
			$assetTypeId == 41 || // Hair Accessory
			$assetTypeId == 42 || // Face Accessory
			$assetTypeId == 43 || // Neck Accessory
			$assetTypeId == 44 || // Shoulder Accessory
			$assetTypeId == 45 || // Front Accessory
			$assetTypeId == 46 || // Back Accessory
			$assetTypeId == 47    // Waist Accessory
		) {
			$assetTypeId = 8;
		}
		
		$hash = CdnHelper::SaveContentB64($b64Content, 'application/octet-stream');
		$asset = Asset::create([
			'creatorId' => ($uploadToHolder ? 1 : Auth::user()->id),
			'name' => $marketplaceResult['Name'],
			'description' => $marketplaceResult['Description'],
			'approved' => true,
			'priceInTokens' => $marketplaceResult['PriceInRobux'] ?: 0,
			'onSale' => $marketplaceResult['IsForSale'],
			'assetTypeId' => $assetTypeId,
			'assetVersionId' => 0
		]);
		$assetVersion = AssetVersion::create([
			'parentAsset' => $asset->id,
			'localVersion' => 1,
			'contentURL' => $hash
		]);
		$asset->assetVersionId = $assetVersion->id;
		$asset->save();
		
		RobloxAsset::create([
			'robloxAssetId' => $id,
			'localAssetId' => $asset->id
		]);
		
		return $asset;
	}
}
