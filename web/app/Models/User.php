<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\ResetPasswordNotification;
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
}
