<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fetchUser() {
        $POST;

        if (!isset($_POST['token'])) {return Response()->json(false);}

        $POST = $_POST['token'];
        $user = User::where('token', $POST)->first();

        if (!$user) {return Response()->json(false);}

        $array = $user->toArray();

        if (!$user) {return Response()->json(false);}

        return Response()->json(["data"=>$array]);
    }

    public function login(Request $request) {

        $data = Request::all();

        $valid = Validator::make($data, [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        if (!User::where('username', Request::input('username'))->first()) {
            return Response()->json(['message'=>"Sorry, that user wasn't found!", 'badInputs'=>['username']]);
        }

        $user = User::where('username', Request::input('username'))->first();
        
        if (!Auth::attempt(Request::only('username', 'password'))) {
            return Response()->json(['message'=>'Sorry, thats the wrong password!', 'badInputs'=>['password']]);
        }

        Request::session()->regenerate();

        setcookie('gtok', $user->token, time()+(345600*30), "/");

        Auth::login($user);

        return Response()->json('good');

    }

}
