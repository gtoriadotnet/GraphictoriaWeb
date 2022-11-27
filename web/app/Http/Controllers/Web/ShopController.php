<?php

namespace App\Http\Controllers\Web;

use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
	public function index()
	{
		return view('web.shop.index');
	}
	
	public function showAsset(Request $request, Asset $asset, string $assetName = null)
	{
		$assetSlug = Str::slug($asset->name, '-');
		
		if($asset->moderated)
			abort(404);
		
		if($asset->assetTypeId == 9) // Place
			return redirect()->route('games.asset', ['asset' => $asset->id, 'assetName' => $assetSlug]);
		
		if ($assetName != $assetSlug)
			return redirect()->route('shop.asset', ['asset' => $asset->id, 'assetName' => $assetSlug]);
		
		return view('web.shop.asset')->with([
			'title' => sprintf('%s by %s', $asset->name, $asset->user->username),
			'asset' => $asset
		]);
	}
}
