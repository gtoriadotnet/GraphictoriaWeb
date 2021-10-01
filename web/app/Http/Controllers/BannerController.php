<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Returns a JSON array of on-site banners.
     *
     * @return Response
     */
    public function getBanners()
    {
		$banners = Banner::select('type', 'message as text', 'dismissable')
					->get();
		
        return response()->json($banners)
				->header('Access-Control-Allow-Origin', env('APP_URL'))
				->header('Vary', 'origin')
				->header('Content-Type', 'application/json');
    }
}
