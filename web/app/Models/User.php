<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\ResetPasswordNotification;
use App\Models\UserRoleset;
use App\Models\Roleset;
use App\Models\Friend;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'next_reward' => 'datetime',
    ];
	
	/**
	 * Send a password reset notification to the user.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$url = route('auth.password.reset-submit', ['token' => $token]);
		
		$this->notify(new ResetPasswordNotification($url, $this));
	}
	
	public function getFriendRequests()
	{
		return Friend::where('receiver_id', Auth::user()->id)
						->where('accepted', false);
	}
	
	public function getProfileUrl()
	{
		return route('user.profile', ['user' => $this->id]);
	}
	
	public function _hasRolesetInternal($roleName)
	{
		$roleset = Roleset::where('Name', $roleName)->first();
		if(
			UserRoleset::where('Roleset_id', $roleset->id)
						->where('User_id', Auth::user()->id)
						->exists()
		)
			return true;
		
		return false;
	}
	
	public function hasRoleset($roleName)
	{
		if(!Auth::check())
			return false;
		
		$roleName = strtolower($roleName);
		
		// Special cases for Owner and Administrator rolesets
		if($roleName == 'moderator') {
			return (
				$this->_hasRolesetInternal('Owner') ||
				$this->_hasRolesetInternal('Administrator') ||
				$this->_hasRolesetInternal('Moderator')
			);
		} elseif($roleName == 'administrator') {
			return (
				$this->_hasRolesetInternal('Owner') ||
				$this->_hasRolesetInternal('Administrator')
			);
		}
		
		return $this->_hasRolesetInternal($roleName);
	}
}
