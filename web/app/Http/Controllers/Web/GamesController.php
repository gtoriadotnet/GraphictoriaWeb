<?php

namespace App\Http\Controllers\Web;

use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GamesController extends Controller
{
    public function index()
	{
		return view('web.games.index');
	}
	
	public function showGame(Request $request, Asset $asset, string $assetName = null)
	{
		$assetSlug = Str::slug($asset->name, '-');
		
		if($asset->moderated)
			abort(404);
		
		if($asset->assetTypeId != 9) // Place
			return redirect()->route('shop.asset', ['asset' => $asset->id, 'assetName' => $assetSlug]);
		
		if ($assetName != $assetSlug)
			return redirect()->route('games.asset', ['asset' => $asset->id, 'assetName' => $assetSlug]);
		
		return view('web.games.asset')->with([
			'title' => sprintf('%s by %s', $asset->universe->name, $asset->user->username),
			'asset' => $asset
		]);
	}
}
