<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ModerationController extends Controller
{
    public function notice(Request $request)
	{
		return view('web.auth.moderated')->with('punishment', Auth::user()->getPunishment());
	}
	
	public function reactivate(Request $request)
	{
		$punishment = Auth::user()->getPunishment();
		if(!$punishment || !$punishment->expired())
			return redirect()->back();
		
		$punishment->active = false;
		$punishment->save();
		
		return redirect()->back();
	}
}
