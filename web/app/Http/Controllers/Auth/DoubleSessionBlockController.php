<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;

class DoubleSessionBlockController extends Controller
{
    public function create()
    {
        return response()
					->view('auth.ddos_blocked', [], 403);
    }
	
	public function store()
	{
		request()->validate([
				'g-recaptcha-response' => [new \App\Rules\GoogleRecaptcha]
			]);
		
		$record = Session::where('id', session()->getId())->first();
		if($record) {
			$record->bypass_block_screen = true;
			$record->save();
			
			$returnUrl = request()->input('ReturnUrl');
			
			if(!$returnUrl)
				$returnUrl = '/';
			
			return redirect(urldecode($returnUrl), 302);
		} else {
			return redirect()->back()->withErrors('Could not unblock. Try again.');
		}
	}
}
