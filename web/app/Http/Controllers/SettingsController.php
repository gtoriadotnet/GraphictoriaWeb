<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\User\UserSession;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Category;
use App\Models\Friend;
use App\Models\Feed;
use App\Models\Staff;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Auth;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function settingsAbout(Request $request) {

        $data = $request->all();

        $valid = Validator::make($data, [
            'body' => ['required', 'string', 'min:2', 'max:180'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $user = AuthHelper::GetCurrentUser($request);

        $user->about = $_POST['body'];
        $user->save();

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);

    }

    public function settingsPassword(Request $request) {

        $data = $request->all();

        $valid = Validator::make($data, [
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:8'],
            'checkNewPassword' => ['required', 'string'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $user = AuthHelper::GetCurrentUser($request);

        if (!$user) return Response()->json(['message'=>'User not found!', 'badInputs'=>['title']]);

        if (!Hash::check($request->input('currentPassword'), $user->password))
            return Response()->json(['message'=>'Thats not the right password!', 'badInputs'=>['currentPassword']]);

        if ($request->input('newPassword') != $request->input('checkNewPassword'))
            return Response()->json(['message'=>'Those dont match!', 'badInputs'=>['checkNewPassword', 'newPassword']]);        

        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);

    }

    public function settingsEmail(Request $request) {

        $data = $request->all();

        $valid = Validator::make($data, [
            'currentPassword' => ['required', 'string'],
            'newEmail' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $user = AuthHelper::GetCurrentUser($request);

        if (!$user) return Response()->json(['message'=>'User not found!', 'badInputs'=>['title']]);

        if (!Hash::check($request->input('currentPassword'), $user->password))
            return Response()->json(['message'=>'Thats not the right password!', 'badInputs'=>['currentPassword']]);        

        $user->email = $request->input('newEmail');
        $user->email_verified_at = null;
        $user->save();

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);

    }

}
