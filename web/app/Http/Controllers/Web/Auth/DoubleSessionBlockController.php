<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DoubleSessionBlockController extends Controller
{
    public function index()
    {
        return response()
					->view('web.auth.ddos_blocked', [], 403);
    }
	
	public function store()
	{
		request()->validate([
				'g-recaptcha-response' => [new \App\Rules\GoogleRecaptcha]
			]);
		
		request()->session()->put('bypass-block-screen', true);
		
		$returnUrl = request()->input('ReturnUrl');
		
		if(!$returnUrl)
			$returnUrl = '/';
		
		return redirect(urldecode($returnUrl), 302);
	}
}
