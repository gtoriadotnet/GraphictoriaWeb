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

    public function __construct()
    {
        $this->middleware('guest');
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
        
        if (!Auth::attempt(Request::only('username', 'password'))) {
            return Response()->json(['message'=>'Sorry, thats the wrong password!', 'badInputs'=>['password']]);
        }

        Request::session()->regenerate();

        return Response()->json('good');

    }

}
