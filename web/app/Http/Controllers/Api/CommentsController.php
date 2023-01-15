<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Comment;

class CommentsController extends Controller
{
    protected function listJson(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'assetId' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			]
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		$comments = Comment::where('asset_id', $valid['assetId'])
							->where('deleted', false)
							->orderByDesc('id')
							->cursorPaginate(15);
		
		$prevCursor = $comments->previousCursor();
		$nextCursor = $comments->nextCursor();
		
		$result = [
			'data' => [],
			'prev_cursor' => ($prevCursor ? $prevCursor->encode() : null),
			'next_cursor' => ($nextCursor ? $nextCursor->encode() : null)
		];
		
		foreach($comments as $comment) {
			$poster = $comment->user->userToJson();
			
			$postDate = $comment['updated_at'];
			if(Carbon::now()->greaterThan($postDate->copy()->addDays(2)))
				$postDate = $postDate->isoFormat('lll');
			else
				$postDate = $postDate->calendar();
			
			array_push($result['data'], [
				'commentId' => $comment->id,
				'poster' => $poster,
				'content' => $comment->content,
				'time' => $postDate
			]);
		}
		
		return $result;
	}
	
	protected function share(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'assetId' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			],
			'content' => ['required', 'max:200']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		$comment = new Comment();
		$comment->asset_id = $valid['assetId'];
		$comment->author_id = Auth::id();
		$comment->content = $valid['content'];
		$comment->save();
		
		return response(['success' => true]);
	}
}
