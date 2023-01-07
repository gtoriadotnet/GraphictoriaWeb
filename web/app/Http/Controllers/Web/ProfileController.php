<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    protected function index(Request $request, User $user)
	{
		if($user->hasActivePunishment() && $user->getPunishment()->isDeletion())
			abort(404);
		
		return view('web.user.profile')->with([
			'title' => sprintf('Profile of %s', $user->username),
			'user' => $user
		]);
	}
}
