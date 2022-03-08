<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function createPost() {

        $data = Request::all();

        $valid = Validator::make($data, [
            'title' => ['required', 'string', 'min:3', 'max:38'],
            'body' => ['required', 'string', 'min:3', 'max:380'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        if (!isset($_POST['creator_id'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $user = User::where('id', $_POST['creator_id'])->first();
        
        if (!$user) {return Response()->json(['message'=>'User not found!', 'badInputs'=>['title']]);}

        $post = new Post;
        $post->title = $_POST['title'];
        $post->body = $_POST['body'];
        $post->creator_id = $_POST['creator_id'];
        //will add category support later
        $post->category_id = 1;
        $post->category_type = 'App\Models\Category';
        $post->save();

        return Response()->json('good');

    }
}
