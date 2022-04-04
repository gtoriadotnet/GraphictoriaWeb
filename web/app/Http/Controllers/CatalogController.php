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
use App\Models\Item;
use App\Models\Selling;
use App\Models\Inventory;
use App\Models\Staff;
use App\Models\Prices;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Auth;

class CatalogController extends Controller
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

    public function buy(Request $request, $id) {

        $user = AuthHelper::GetCurrentUser($request);

        if (!$user) return Response()->json(['message'=>'System Error', 'badInputs'=>['title']]);

        $item = Item::whereId($id)->first();

        if (!$item) return Response()->json(['message'=>'No Item.', 'badInputs'=>['title']]);

        if (!isset($_POST['decision'])) return Response()->json(['message'=>'System Error', 'badInputs'=>['title']]);

        $decision = $_POST['decision'];

        if ($item->current_price > $user->bank) return Response()->json(['message'=>"Sorry, you don't have enough currency!", 'badInputs'=>['title']]);

        switch($decision) {
            case 'nonLimited':

                $newInventory = new Inventory;
                $newInventory->item_id = $item->id;
                $newInventory->owner_id = $user->id;
                $newInventory->owner_type = 'App\Models\User';
                $newInventory->status = 1;
                $user->inventory()->save($newInventory);
                $newInventory->uid = 'nonLimited';
                $newInventory->save();

                $user->decrement('bank', $item->current_price);
                $user->save();
                
                $replies = $item->sellingPrices()->orderBy('price', 'asc')->paginate(10);

                $itemA = $item->toArray();

                $itemA['creator'] = User::where('id', $item->creator_id)->first();

                foreach ($replies as &$reply) {
                    $creator = User::where('id', $reply['seller_id'])->first();
                    $reply['created_at'] = explode('T', $reply['created_at'])[0];
                    $reply['seller_name'] = $creator->username;
                }

                return Response()->json(['message'=>"Success!", 'badInputs'=>[], "item"=>$itemA, "sellingPrices"=>$replies]);

                break;
            case 'limited':

                if ($item->stock <= 0) return Response()->json(['message'=>"Sorry, there's no more in stock for this item!", 'badInputs'=>['title']]);

                $newInventory = new Inventory;
                $newInventory->item_id = $item->id;
                $newInventory->owner_id = $user->id;
                $newInventory->owner_type = 'App\Models\User';
                $newInventory->status = 1;
                $user->inventory()->save($newInventory);
                $newInventory->uid = $newInventory->id;
                $newInventory->save();

                $user->decrement('bank', $item->current_price);
                $user->save();

                $item->decrement('stock');
                $item->save();

                $replies = $item->sellingPrices()->orderBy('price', 'asc')->paginate(10);

                $itemA = $item->toArray();

                $itemA['creator'] = User::where('id', $item->creator_id)->first();

                foreach ($replies as &$reply) {
                    $creator = User::where('id', $reply['seller_id'])->first();
                    $reply['created_at'] = explode('T', $reply['created_at'])[0];
                    $reply['seller_name'] = $creator->username;
                }

                return Response()->json(['message'=>"Success!", 'badInputs'=>[], "item"=>$itemA, "sellingPrices"=>$replies]);

                break;
            case 'selling':

                if (!isset($_POST['sellingId'])) return Response()->json(['message'=>'No Selling ID.', 'badInputs'=>['title']]);

                $sellingId = $_POST['sellingId'];

                $sellingItem = Selling::whereId($sellingId)->first();

                if (!$sellingItem) return Response()->json(['message'=>"That selling item doesn't exist!", 'badInputs'=>['title']]);

                if ($sellingItem->seller_id == $user->id) return Response()->json(['message'=>"Thats you!", 'badInputs'=>['title']]);

                $seller = User::where('id', $sellingItem->seller_id)->first();

                $ownedItem = Inventory::where('owner_id', $sellingItem->seller_id)->where('item_id', $item->id)->first();

                if ($sellingItem->price > $user->bank) return Response()->json(['message'=>"Sorry, you don't have enough currency!", 'badInputs'=>['title']]);

                $newInventory = new Inventory;
                $newInventory->item_id = $item->id;
                $newInventory->owner_id = $user->id;
                $newInventory->owner_type = 'App\Models\User';
                $newInventory->status = 1;
                $user->inventory()->save($newInventory);
                $newInventory->uid = $sellingItem->uid;
                $newInventory->save();

                $user->decrement('bank', $sellingItem->price);
                $user->save();

                $seller->increment('bank', $sellingItem->price);
                $seller->save();

                /*$priceNew = new Prices;
                $priceNew->price = $sellingItem->price;
                $item->prices()->save($priceNew);*/

                $sellingItem->delete();

                $ownedItem->delete();

                $sellingItemNew = Selling::whereId($sellingId)->first();

                if (count($item->sellingPrices) <= 0) {$item->current_price = null;$item->save();}else{$item->current_price = $sellingItemNew->price;$item->save();}

                $replies = $item->sellingPrices()->orderBy('price', 'asc')->paginate(10);

                $itemA = $item->toArray();

                $itemA['creator'] = User::where('id', $item->creator_id)->first();

                foreach ($replies as &$reply) {
                    $creator = User::where('id', $reply['seller_id'])->first();
                    $reply['created_at'] = explode('T', $reply['created_at'])[0];
                    $reply['seller_name'] = $creator->username;
                }

                return Response()->json(['message'=>"Success!", 'badInputs'=>[], "item"=>$itemA, "sellingPrices"=>$replies]);

                break;
            default:
                break;
        }

    }

    public function sell(Request $request, $id) {

        $user = AuthHelper::GetCurrentUser($request);

        if (!$user) return Response()->json(['message'=>'System Error', 'badInputs'=>['title']]);

        $item = Item::whereId($id)->first();

        if (!$item) return Response()->json(['message'=>'System Error', 'badInputs'=>['title']]);

        if (!$user->ownsItem($id)) return Response()->json(['message'=>"You don't own this item!", 'badInputs'=>['title']]);

        $inventory = Inventory::where('item_id', $id)->where('owner_id', $user->id)->first();

        $data = $request->all();

        $valid = Validator::make($data, [
            'price' => ['required', 'integer', 'min:1'],
        ]);

        if ($valid->stopOnFirstFailure()->fails()) {
            $error = $valid->errors()->first();
            $messages = $valid->messages()->get('*');
            return Response()->json(['message'=>$error, 'badInputs'=>[array_keys($messages)]]);
        }

        $selling = new Selling;
        $selling->seller_id = $user->id;
        $selling->uid = $inventory->uid;
        $selling->price = $request->input('price');
        $item->sellingPrices()->save($selling);

        $sellingItemNew = Selling::where('item_id', $id)->first();

        if ($item->sellingPrices()->count() <= 0) {$item->current_price = null;$item->save();}else{$item->current_price = $sellingItemNew->price;$item->save();}

        $replies = $item->sellingPrices()->orderBy('price', 'asc')->paginate(10);

        $itemA = $item->toArray();

        $itemA['creator'] = User::where('id', $item->creator_id)->first();

        foreach ($replies as &$reply) {
            $creator = User::where('id', $reply['seller_id'])->first();
            $reply['created_at'] = explode('T', $reply['created_at'])[0];
            $reply['seller_name'] = $creator->username;
        }

        return Response()->json(['message'=>"Success!", 'badInputs'=>[], "item"=>$itemA, "sellingPrices"=>$replies]);

    }

}
