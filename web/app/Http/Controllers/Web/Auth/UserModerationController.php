<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserModerationController extends Controller
{
    public function create()
	{
		return view('web.auth.moderated');
	}
}
