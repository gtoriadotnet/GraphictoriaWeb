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

    public function createPost(Request $request) {

        $data = $request->all();

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

        $meta = AuthHelper::GetCurrentUser($request);

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

    public function createFeed(Request $request) {

        $data = $request->all();

        $valid = Validator::make($data, [
            'body' => ['required', 'string', 'min:3', 'max:245'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $user = AuthHelper::GetCurrentUser($request);

        if (!$user) {return Response()->json(['message'=>'System error', 'badInputs'=>['title']]);}

        $feed = new Feed;
        $feed->user_id = $user->id;
        $feed->body = $request->input('body');
        $feed->save();

        $friends = Friend::where('status', 1)->where('recieved_id', $user->id)->orWhere('sent_id', $user->id)->get()->toArray();
        $actualFriends = [];

        foreach ($friends as $friend) {
            if ($friend['recieved_id'] == $user->id) {
                array_push($actualFriends, $friend['sent_id']);
            }else{
                array_push($actualFriends, $friend['recieved_id']);
            }
        }

        $newFeed = Feed::whereIn('user_id', $actualFriends)->orWhere('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(15);

        foreach ($newFeed as &$singleFeed)  {
            $creator = User::where('id', $singleFeed['user_id'])->first();
            $singleFeed['creatorName'] = $creator->username;
        }

        return Response()->json(['message'=>'Success!', 'badInputs'=>[], "data"=>$newFeed]);

    }

    public function createReply($id) {

        $data = $request->all();

        $valid = Validator::make($data, [
            'body' => ['required', 'string', 'min:3', 'max:380'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $meta = AuthHelper::GetCurrentUser($request);

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
