<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    public function fetchAsset(Request $request) {
		// Temporary
		// TODO: Fetch assets from DB, if it doesn't exist then redirect to roblox's assetdelivery.
		return redirect('https://assetdelivery.roblox.com/v1/asset/?id=' . $request->input('id'));
	}
}
