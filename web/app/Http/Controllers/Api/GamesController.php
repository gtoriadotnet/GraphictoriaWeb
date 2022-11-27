<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Universe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GamesController extends Controller
{
	protected static function getAssets()
	{
		// TODO: XlXi: sort also based on how many people are in open servers
		return Universe::where('public', true)
						->whereRelation('starterPlace', 'moderated', false)
						->join('assets', 'assets.id', '=', 'universes.startPlaceId')
						->orderBy('assets.created_at', 'desc')
						->orderBy('assets.visits', 'desc');
	}
	
    protected function listJson(Request $request)
	{
		$assets = self::getAssets()->paginate(30);
		
		$data = [];
		foreach($assets as $asset) {
			$asset = $asset->starterPlace;
			$creator = $asset->user;
			
			array_push($data, [
				'Name' => $asset->universe->name,
				'Creator' => [
					'Name' => $creator->username,
					'Url' => $creator->getProfileUrl()
				],
				'Playing' => 0,
				'Ratio' => 0,
				'Url' => route('games.asset', ['asset' => $asset->id, 'assetName' => Str::slug($asset->name, '-')])
			]);
		}
		
		return response([
			'pages' => ($assets->hasPages() ? $assets->lastPage() : 1),
			'data' => $data
		]);
	}
}
