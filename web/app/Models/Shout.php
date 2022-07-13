<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Shout extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
		'updated_at' => 'datetime',
    ];
	
	protected static function getPosts()
	{
		return self::where([['poster_type', 'user'], ['deleted', '0']])
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
						});
	}
}
