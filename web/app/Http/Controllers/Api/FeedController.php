<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Shout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    protected function listjson()
	{
		// TODO: XlXi: Group shouts.
		$postsQuery = Shout::where([['poster_type', 'user'], ['deleted', '0']])
							->where(function($query) {
								$query->where('poster_id', Auth::id())
								->orWhereExists(function($query) {
									$query->select(DB::raw('*'))
										->from('friends')
										->where('accepted', 1)
										->where(function($query) {
											$query->whereColumn('shouts.poster_id', 'friends.sender_id')
												->orWhereColumn('shouts.poster_id', 'friends.receiver_id');
										});
								});
							})
							->orderByDesc('created_at')
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
			// TODO: XlXi: icons/colors
			// TODO: XlXi: groups
			
			$poster = [];
			if($post['poster_type'] == 'user') {
				$user = User::where('id', $post['poster_id'])->first();
				
				$poster = [
					'type' => 'User',
					'name' => $user->username,
					'thumbnail' => 'https://www.gtoria.local/images/testing/headshot.png'
				];
			}
			
			/* */
			
			array_push($posts['data'], [
				'postId' => $post['id'],
				'poster' => $poster,
				'content' => $post['content'],
				'time' => $post['updated_at']
			]);
		}
		
		return response($posts);
	}
	
	protected function handle()
	{
		//
	}
}
