<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
	{
		if($request->isMethod('post')) {
			$request->validate(
				[
					'username' => ['required', 'string', 'exists:App\Models\User,id'],
					'password' => ['required', 'string']
				]
			);
			
			
		}
		
		return view('auth.login');
	}
	
	public function register(Request $request)
	{
		$errors = [];
		
		return view('auth.register');
	}
}
