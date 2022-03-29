<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use HasFactory;
	
	/**
	 * The database connection that should be used by the migration.
	 *
	 * @var string
	 */
	protected $connection = 'mysql-membership';

	protected $hidden = [
        'password',
        'remember_token',
        'email',
        'email_verified_at'
    ];

	public function getFriends($decision, $pending, $id) {
		
		switch($decision) {
			case 'id': 
				$friends = Friend::where('status', 1)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
				$actualFriends = [];

				foreach ($friends as $friend) {
					if ($friend['recieved_id'] == $this->id) {
						array_push($actualFriends, $friend['sent_id']);
					}else{
						array_push($actualFriends, $friend['recieved_id']);
					}
				}

				return $actualFriends;

				break;
			case 'account':
				$friends = Friend::where('status', 1)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
				$actualFriends = [];

				foreach ($friends as $friend) {
					if ($friend['recieved_id'] == $this->id) {
						$friendUser = User::where('id', $friend['sent_id'])->first();
						array_push($actualFriends, $friendUser);
					}else{
						$friendUser = User::where('id', $friend['recieved_id'])->first();
						array_push($actualFriends, $friendUser);
					}
				}

				return $actualFriends;

				break;
			case 'remove':
				$friends = Friend::where('status', 1)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
				$actualFriends = [];

				foreach ($friends as $friend) {
					if ($friend['recieved_id'] == $this->id) {
						$friendUserMeta = Friend::where('sent_id', $id)->delete();
					}else{
						$friendUserMeta = Friend::where('recieved_id', $id)->delete();
					}
				}

				return;

				break;
			case 'accept':
				$friends = Friend::where('status', 0)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
				$actualFriends = [];

				foreach ($friends as $friend) {
					if ($friend['recieved_id'] == $this->id) {
						$friendUserMeta = Friend::where('sent_id', $id)->first();
						$friendUserMeta->status = 1;
						$friendUserMeta->save();
					}else{
						$friendUserMeta = Friend::where('recieved_id', $id)->first();
						$friendUserMeta->status = 1;
						$friendUserMeta->save();
					}
				}

				return;

				break;
			case 'pending':
				switch($pending) {
					case 'id':
						$friends = Friend::where('status', 0)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
						$actualFriends = [];

						foreach ($friends as $friend) {
							if ($friend['recieved_id'] == $this->id) {
								array_push($actualFriends, $friend['sent_id']);
							}else{
								array_push($actualFriends, $friend['recieved_id']);
							}
						}

						return $actualFriends;

						break;
					case 'account':
						$friends = Friend::where('status', 0)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
						$actualFriends = [];

						foreach ($friends as $friend) {
							if ($friend['recieved_id'] == $this->id) {
								$friendUser = User::where('id', $friend['sent_id'])->first();
								array_push($actualFriends, $friendUser);
							}else{
								$friendUser = User::where('id', $friend['recieved_id'])->first();
								array_push($actualFriends, $friendUser);
							}
						}

						return $actualFriends;

						break;

					case 'checkSent':
						$friends = Friend::where('status', 0)->where('recieved_id', $this->id)->orWhere('sent_id', $this->id)->get()->toArray();
						$actualFriends = [];

						foreach ($friends as $friend) {
							if ($friend['recieved_id'] == $this->id && $friend['sent_id'] == $id) {
								return true;
							}else{
								return false;
							}
						}

						break;
					default:
						break;
				}
			default:
				break;
		}

	}

}
