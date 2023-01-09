<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Shout;
use App\Models\User;

class FeedController extends Controller
{
    protected function listJson()
	{
		// TODO: XlXi: Group shouts.
		$postsQuery = Shout::getPosts()
							->orderByDesc('id')
							->cursorPaginate(15);
		
		/* */
		
		$prevCursor = $postsQuery->previousCursor();
		$nextCursor = $postsQuery->nextCursor();
		
		$posts = [
			'data' => [],
			'prev_cursor' => ($prevCursor ? $prevCursor->encode() : null),
			'next_cursor' => ($nextCursor ? $nextCursor->encode() : null)
		];
		
		foreach($postsQuery as $post) {
			// TODO: XlXi: groups
			
			$poster = [];
			if($post['poster_type'] == 'user') {
				$user = User::where('id', $post['poster_id'])->first();
				$poster = $user->userToJson();
			}
			
			/* */
			
			$postDate = $post['updated_at'];
			if(Carbon::now()->greaterThan($postDate->copy()->addDays(2)))
				$postDate = $postDate->isoFormat('lll');
			else
				$postDate = $postDate->calendar();
			
			/* */
			
			array_push($posts['data'], [
				'postId' => $post['id'],
				'poster' => $poster,
				'content' => $post['content'],
				'time' => $postDate
			]);
		}
		
		return response($posts);
	}
	
	protected function share(Request $request)
	{
		$validated = $request->validate([
			'content' => ['required', 'max:200']
		]);
		
		$shout = new Shout();
		$shout->poster_id = Auth::id();
		$shout->poster_type = 'user';
		$shout->content = $validated['content'];
		$shout->save();
		
		return response(['success' => true]);
	}
}
