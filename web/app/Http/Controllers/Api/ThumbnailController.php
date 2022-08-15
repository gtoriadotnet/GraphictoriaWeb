<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\ArbiterRender;
use App\Models\Asset;
use App\Models\RenderTracker;

class ThumbnailController extends Controller
{
	public function renderAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			],
			'type' => 'regex:/(3D|2D)/i'
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$asset = Asset::where('id', $valid['id'])->first();
		
		$valid['type'] = strtolower($valid['type']);
		
		if($asset->thumbnail2DHash && $valid['type'] == '2d')
			return response(['status' => 'success', 'data' => route('content', $asset->thumbnail2DHash)]);
		
		if($asset->thumbnail3DHash && $valid['type'] == '3d')
			return response(['status' => 'success', 'data' => route('content', $asset->thumbnail3DHash)]);
		
		$tracker = RenderTracker::where('type', sprintf('asset%s', $valid['type']))
								->where('target', $valid['id']);
		
		if(!$tracker->exists()) {
			$tracker = new RenderTracker;
			$tracker->type = sprintf('asset%s', $valid['type']);
			$tracker->target = $valid['id'];
			$tracker->save();
			
			ArbiterRender::dispatch($tracker, $valid['type'] == '3d');
		}
		
		return response(['status' => 'loading']);
	}
	
	public function renderUser()
	{
		//
	}
	
	public function tryAsset()
	{
		//
	}
}
