<?php

/*
	Graphictoria 2022
	Authentication helper
	Written because FUCK WHOEVER IS BEHIND THE Illuminate\Foundation\Auth\User DEV TEAM AHHHHHH
	my frustration is immeasureable ~ xlxi
*/

namespace App\Helpers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\User\UserSession;
use App\Helpers\Random;

class AuthHelper
{
	/**
     * "Guards" a page in the sense where it kills the request if the user is logged in.
     *
     * @return boolean
     */
	public static function Guard(Request $request) {
		if(AuthHelper::GetCurrentUser($request))
			return true;
	}
	
	/**
     * Returns the current user.
     *
     * @return User?
     */
	public static function GetCurrentUser(Request $request) {
		if($request->session()->exists('authentication')) {
			$session = UserSession::where('token', $request->session()->get('authentication'))->first();

			if($session)
				return User::where('id', $session->user)->first();
			
			return;
		}
		
		return;
	}

	/**
     * Remove a session.
     *
     * @return User?
     */
	public static function RemoveSession(Request $request) {
		if($request->session()->exists('authentication')) {
			$session = UserSession::where('token', $request->session()->get('authentication'))->first();
			$session->delete();
			return;
		}
		
		return;
	}
	
	/**
     * Grants a session.
     *
     * @return UserSession
     */
	public static function GrantSession($request, $id) {
		$session = new UserSession();
		$session->user = $id;
		// formerly cookies
		$session->token = 'DO_NOT_SHARE_OR_YOUR_ITEMS_WILL_BE_STOLEN_|' . Random::Str(64);
		$session->ip = $request->ip();
		$session->last_seen = Carbon::now();
		$session->save();
		
		return $session;
	}

}
