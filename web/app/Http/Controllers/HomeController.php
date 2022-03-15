<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Category;
use App\Models\Staff;
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

    public function settingsAbout() {

        $data = Request::all();

        $valid = Validator::make($data, [
            'body' => ['required', 'string', 'min:2', 'max:180'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        if (!isset($_POST['token'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $user = User::where('token', $_POST['token'])->first();
        
        if (!$user) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $user->about = $_POST['body'];
        $user->save();

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);

    }

    public function createPost() {

        $data = Request::all();

        $valid = Validator::make($data, [
            'title' => ['required', 'string', 'min:3', 'max:38'],
            'body' => ['required', 'string', 'min:3', 'max:380'],
            'category' => ['required']
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        if (!isset($_POST['token'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $meta = User::where('token', $_POST['token'])->first();
        
        if (!$meta) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        if (!isset($_POST['creator_id'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $user = User::where('id', $_POST['creator_id'])->first();
        
        if (!$user) {return Response()->json(['message'=>'User not found!', 'badInputs'=>['title']]);}

        if (!isset($_POST['category'])) {return Response()->json(['message'=>'Category not found!', 'badInputs'=>['category']]);}

        $categoryId = $_POST['category'];

        $category = Category::where('id', $categoryId)->first();

        $staff = Staff::where('user_id', $user->id)->first();

        if ($category->staffOnly == '1' && !$staff) {return Response()->json(['message'=>'You cant use that category.', 'badInputs'=>['category']]);}

        $post = new Post;
        $post->title = $_POST['title'];
        $post->body = $_POST['body'];
        $post->creator_id = $_POST['creator_id'];
        $category->posts()->save($post);

        return Response()->json(['message'=>'Success!', 'badInputs'=>[], 'post_id'=>$post->id]);

    }

    public function createReply($id) {

        $data = Request::all();

        $valid = Validator::make($data, [
            'body' => ['required', 'string', 'min:3', 'max:380'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        if (!isset($_POST['token'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $meta = User::where('token', $_POST['token'])->first();
        
        if (!$meta) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        if (!isset($_POST['creator_id'])) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $user = User::where('id', $_POST['creator_id'])->first();
        
        if (!$user) {return Response()->json(['message'=>'User not found!', 'badInputs'=>['title']]);}

        $post = Post::where('id', $id)->first();

        if (!$post) {return Response()->json(['message'=>'Post not found!', 'badInputs'=>['body']]);}

        if ($post->locked && $user->id != $meta->id) {return Response()->json(['message'=>'This post is locked!', 'badInputs'=>['body']]);}

        $reply = new Reply;
        $reply->body = $_POST['body'];
        $reply->creator_id = $user->id;
        $post->replies()->save($reply);

        $post->touch();

        return Response()->json(['message'=>'Success!', 'badInputs'=>[], 'post_id'=>$post->id]);

    }
}
