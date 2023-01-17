<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

use App\Http\Controllers\Controller;
use App\Models\AvatarAsset;
use App\Models\DefaultUserAsset;
use App\Models\UserAsset;
use App\Models\User;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{	
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('web.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9]+[ _.-]?[a-zA-Z0-9]+$/i', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
			'username.min' => 'Username can only be 3 to 20 characters long.',
			'username.max' => 'Username can only be 3 to 20 characters long.',
			'username.regex' => 'Username must be alphanumeric and cannot begin or end with a special character. (a-z, 0-9, dots, hyphens, spaces, and underscores are allowed)'
		]);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
		
		foreach(DefaultUserAsset::all() as $defaultAsset)
		{
			UserAsset::createSerialed($user->id, $defaultAsset->asset_id);
			
			if($defaultAsset->wearing)
			{
				AvatarAsset::create([
					'owner_id' => $user->id,
					'asset_id' => $defaultAsset->asset_id
				]);
			}
		}
		
		$user->redraw();
		
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
