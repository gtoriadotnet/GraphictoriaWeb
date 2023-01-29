<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Helpers\GridHelper;
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
					return $query->where('moderated', false);
				})
			],
			'type' => 'regex:/(3D|2D)/i'
		];
	}
	
	private function userValidationRules()
	{
		return [
			'id' => [
				'required',
				Rule::exists('App\Models\User', 'id'),
			],
			'position' => ['sometimes', 'regex:/(Full|Bust)/i'],
			'type' => 'regex:/(3D|2D)/i'
		];
	}
	
	private function handleRender(Request $request, string $renderType, bool $assetId = null)
	{
		$validator = Validator::make($request->all(), $this->{strtolower($renderType) . 'ValidationRules'}());
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$model = ('App\\Models\\' . $renderType)::where('id', $valid['id'])->first();
		
		$valid['type'] = strtolower($valid['type']);
		
		if($renderType == 'User') {
			if($model->hasActivePunishment() && $model->getPunishment()->isDeletion())
			{
				$validator->errors()->add('id', 'User is moderated');
				return ValidationHelper::generateValidatorError($validator);
			}
			
			if(!array_key_exists('position', $valid))
				$valid['position'] = 'Full';
			
			$valid['position'] = strtolower($valid['position']);
			
			if($valid['position'] != 'full' && $valid['type'] == '3d')
			{
				$validator->errors()->add('type', 'Cannot render non-full avatar as 3D.');
				return ValidationHelper::generateValidatorError($validator);
			}
			
			switch($valid['position'])
			{
				case 'full':
					if($model->thumbnail2DHash && $valid['type'] == '2d')
						return response(['status' => 'success', 'data' => route('content', $model->thumbnail2DHash)]);
					break;
				case 'bust':
					if($model->thumbnailBustHash && $valid['type'] == '2d')
						return response(['status' => 'success', 'data' => route('content', $model->thumbnailBustHash)]);
					break;
			}
		} elseif($renderType == 'Asset') {
			if($model->renderId)
				$model = Asset::where('id', $model->renderId)->first();
			
			if($model->moderated)
				return response(['status' => 'success', 'data' => '/thumbs/DeletedThumbnail.png']);
			
			if(!$model->approved)
				return response(['status' => 'success', 'data' => '/thumbs/PendingThumbnail.png']);
			
			if(!$model->assetType->renderable)
				return response(['status' => 'success', 'data' => '/thumbs/UnavailableThumbnail.png']);
			
			if(!$model->{$valid['type'] == '3d' ? 'canRender3D' : 'isRenderable'}()) {
				$validator->errors()->add('id', 'This asset cannot be rendered.');
				return ValidationHelper::generateValidatorError($validator);
			}
			
			if($model->thumbnail2DHash && $valid['type'] == '2d')
				return response(['status' => 'success', 'data' => route('content', $model->thumbnail2DHash)]);
		}
		
		if($model->thumbnail3DHash && $valid['type'] == '3d')
			return response(['status' => 'success', 'data' => route('content', $model->thumbnail3DHash)]);
		
		$trackerType = sprintf('%s%s', strtolower($renderType), $valid['type']);
		if($renderType == 'User' && $valid['position'] == 'bust')
			$trackerType .= 'bust';
		$tracker = RenderTracker::where('type', $trackerType)
								->where('target', $model->id)
								->where('created_at', '>', Carbon::now()->subMinute());
		
		if(!$tracker->exists()) {
			$tracker = RenderTracker::create([
				'type' => $trackerType,
				'target' => $model->id
			]);
			
			ArbiterRender::dispatch(
				$tracker,
				$valid['type'] == '3d',
				($renderType == 'User' ? $valid['position'] == 'full' ? 'Avatar' : 'Bust' : $model->typeString()),
				$model->id
			);
		}
		
		return response(['status' => 'loading']);
	}
	
	public function renderAsset(Request $request)
	{
		return $this->handleRender($request, 'Asset');
	}
	
	public function renderUser(Request $request)
	{
		return $this->handleRender($request, 'User');
	}
	
	public function tryAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			]
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		return $this->handleRender($request, 'User', $valid['id']);
	}
}
