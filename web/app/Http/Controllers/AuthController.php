<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Helpers\AuthHelper;
use App\Models\User;

class AuthController extends Controller
{
	/**
     * Creates an account for the user.
     *
     * @return Response
     */
    public function Register(Request $request) {
		if(AuthHelper::Guard($request))
			return Response(null, 400);
		
		/* */
		
		$data = $request->all();
		
		if ($request->input('password') != $request->input('confirmation'))
            return Response()->json(['message'=>'The passwords you supplied don\'t match!', 'badInputs'=>['password','confirmation']]);
		
		$valid = Validator::make(
			$data,
			[
				'username' => ['required', 'string', 'regex:/[a-zA-Z0-9._]+/', 'max:20'],
				'email' => ['required', 'string', 'email', 'max:255'],
				'password' => ['required', 'string', 'min:8'],
			]
		);
		
		if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }
		
		if(User::where('username', $data['username'])->first())
			return Response()->json(['message'=>'This user already exists.', 'badInputs'=>['username']]);
		
		if(User::where('email', $data['email'])->first())
			return Response()->json(['message'=>'This email is already in use!', 'badInputs'=>['email']]);
		
		$user = new User();
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
		$user->about = 'I\'m new to Graphictoria!';
        $user->save();
		
		$request->session()->regenerate();
		
		$newSession = AuthHelper::GrantSession($request, $user->id);
		$request->session()->put('authentication', $newSession->token);

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);
	}
	
	/**
     * Logs the user in.
     *
     * @return Response
     */
    public function Login(Request $request) {
		if(AuthHelper::Guard($request))
			return Response(null, 400);
		
		/* */
		
		$data = $request->all();
		
		$valid = Validator::make(
			$data,
			[
				'username' => ['required', 'string'],
				'password' => ['required', 'string'],
			]
		);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
			
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }
		
		/* */
		
		$user = User::where('username', $request->input('username'))->first();
		if (!$user)
            return Response()->json(['message'=>'That user doesn\'t exist.', 'badInputs'=>['username']]);
		
		if (!$user->password != Hash::make($data['password']))
            return Response()->json(['message'=>'The password you tried is incorrect.', 'badInputs'=>['password']]);
			
		$request->session()->regenerate();
		
		$newSession = AuthHelper::GrantSession($request, $user->id);
		$request->session()->put('authentication', $newSession);

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);
	}
	
	/**
     * Logs the user out and kills the session.
     *
     * @return Response
     */
    public function Logout(Request $request) {
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
	}
}
