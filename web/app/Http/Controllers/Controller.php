<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Staff;
use App\Models\CatalogCategory;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Carbon;
use Auth;
use Request;
use DateTime;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fetchUser() {
        $POST;

        if (!isset($_POST['decision'])) {return Response()->json(false);}

        $decision = $_POST['decision'];

        switch($decision) {
            case "metaUser":
                if (!isset($_POST['token'])) {return Response()->json(false);}
                $POST = $_POST['token'];
                $user = User::where('token', $POST)->first();
                if (!$user) {return Response()->json(false);}
                $array = $user->toArray();
                $staff = Staff::where('user_id', $user->id)->first();
                if ($staff) {$array['power'] = $staff->power_level;}
                $array['bank'] = $user->bank;
                $array['email'] = $user->email;
                return Response()->json(["data"=>$array]);
                break;
            case "fetchedUser":
                if (!isset($_POST['userId'])) {return Response()->json(false);}
                $POST = $_POST['userId'];
                $user = User::where('id', $POST)->first();
                if (!$user) {return Response()->json(false);}
                $array = $user->toArray();
                $staff = Staff::where('user_id', $user->id)->first();
                if ($staff) {$array['power'] = $staff->power_level;}
                return Response()->json(["data"=>$array]);
                break;
            default:
                return Response()->json(false);
                break;
        }

        return Response()->json(["data"=>$array]);
    }

    public function fetchCategoriesFP() {

        if (!isset($_POST['token'])) {return Response()->json(["error"=>"No user."]);}

        $POST = $_POST['token'];

        $user = User::where('token', $POST)->first();

        if (!$user) {return Response()->json(["error"=>"No user."]);}

        $staff = Staff::where('user_id', $user->id)->first();

        if ($staff) {$categories = Category::get();}else{$categories = Category::where('staffOnly', '0')->get();}

        return Response()->json(["categories"=>$categories]);
    }

    public function fetchCategories() {

        $categories = Category::orderBy('staffOnly', 'desc')->get();

        return Response()->json(["categories"=>$categories]);

    }

    public function fetchCategoriesCatalog() {

        $categories = CatalogCategory::get();

        return Response()->json(["categories"=>$categories]);

    }

    public function fetchCategoryCatalog($id) {
        
        $category = CatalogCategory::where('id', $id)->first();

        if (!$category) {return Response()->json(false);}

        $items = $category->items()->orderBy('updated_at', 'desc')->paginate(25);

        foreach ($items as &$item) {
            $item['creator'] = User::where('id', $item['creator_id'])->first();
        }

        return Response()->json(["data"=>$category, "items"=>$items]);
    }

    public function fetchCategory($id) {
        
        $category = Category::where('id', $id)->first();

        if (!$category) {return Response()->json(false);}

        $posts = $category->posts()->orderBy('pinned', 'desc')->orderBy('updated_at', 'desc')->paginate(15);

        foreach ($posts as &$post) {
            $post['creator'] = User::where('id', $post['creator_id'])->first();
        }

        return Response()->json(["data"=>$category, "posts"=>$posts]);
    }

    public function fetchPost($id) {
        
        $post = Post::where('id', $id)->first();

        if (!$post) {return Response()->json(false);}

        $postA = $post->toArray();

        $realDate = explode('T', $postA['created_at'])[0];

        $postA['created_at'] = $realDate;

        $postA['creator'] = User::where('id', $postA['creator_id'])->first();

        $replies = $post->replies()->orderBy('pinned', 'desc')->orderBy('created_at', 'asc')->paginate(10);

        foreach ($replies as &$reply) {
            $creator = User::where('id', $reply['creator_id'])->first();
            $reply['created_at'] = explode('T', $reply['created_at'])[0];
            $reply['creator_name'] = $creator->username;
        }

        return Response()->json(["post"=>$postA,"replies"=>$replies]);
    }


    public function logout(Request $request) {

        $POST;

        if (!isset($_COOKIE['gtok'])) {return Redirect('/login');}

        $POST = $_COOKIE['gtok'];

        $user = User::where('token', $POST)->first();

        if (!$user) {return Redirect('/login');}

        setcookie('gtok', null, time()+(345600*30), "/", $_SERVER['HTTP_HOST']);

        return Redirect('/');

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

        $prws = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 8)); 
        shuffle($prws); 
        $sc = substr(implode($prws), 0, 56);

        $user->token = $sc;
        $user->save();

        setcookie('gtok', $user->token, time()+(345600*30), "/", $_POST['host']);

        Auth::login($user);

        return Response()->json(['message'=>'Success!', 'badInputs'=>[]]);

    }

}
