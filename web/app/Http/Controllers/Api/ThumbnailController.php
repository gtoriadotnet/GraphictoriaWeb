<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
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
	private function assetValidationRules()
	{
		return [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false)
									->where('approved', true);
				})
			],
			'type' => 'regex:/(3D|2D)/i'
		];
	}
	
	private function userValidationRules()
	{
		// TODO: Fail validation if user is moderated.
		return [
			'id' => [
				'required',
				Rule::exists('App\Models\User', 'id')
			],
			'position' => ['sometimes', 'regex:/(Full|Bust)/i'],
			'type' => 'regex:/(3D|2D)/i'
		];
	}
	
	private function handleRender(Request $request, string $renderType)
	{
		$validator = Validator::make($request->all(), $this->{strtolower($renderType) . 'ValidationRules'}());
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$model = ('App\\Models\\' . $renderType)::where('id', $valid['id'])->first();
		
		$valid['type'] = strtolower($valid['type']);
		
		if($renderType == 'User') {
			if($valid['position'] == null)
				$valid['position'] = 'Full';
			
			$valid['position'] = strtolower($valid['position']);
		} elseif($renderType == 'Asset') {
			if(!$model->{$valid['type'] == '3d' ? 'canRender3D' : 'isRenderable'}()) {
				$validator->errors()->add('id', 'This asset cannot be rendered.');
				return ValidationHelper::generateValidatorError($validator);
			}
			
			// TODO: XlXi: Turn this into a switch case and fill in the rest of the unrenderables.
			// 			   Things like HTML assets should just have a generic "default" image.
			//if($model->assetTypeId == 1)
			//	$model = Asset::where('id', $model->parentAsset)->first();
		}
		
		
		if($model->thumbnail2DHash && $valid['type'] == '2d')
			return response(['status' => 'success', 'data' => route('content', $model->thumbnail2DHash)]);
		
		if($model->thumbnail3DHash && $valid['type'] == '3d')
			return response(['status' => 'success', 'data' => route('content', $model->thumbnail3DHash)]);
		
		$trackerType = sprintf('%s%s', strtolower($renderType), $valid['type']);
		$tracker = RenderTracker::where('type', $trackerType)
								->where('target', $valid['id'])
								->where('created_at', '>', Carbon::now()->subMinute());
		
		if(!$tracker->exists()) {
			$tracker = RenderTracker::create([
				'type' => $trackerType,
				'target' => $valid['id']
			]);
			
			ArbiterRender::dispatch(
				$tracker,
				$valid['type'] == '3d',
				($renderType == 'User' ? $valid['position'] : $model->typeString()),
				$model->id
			);
		}
		
		return response(['status' => 'loading']);
	}
	
	public function renderAsset(Request $request)
	{
		return $this->handleRender($request, 'Asset');
	}
	
	public function renderUser()
	{
		return handleRender($request, 'User');
	}
	
	public function tryAsset()
	{
		//
	}
}
