<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
		$redis = Cache::store('redis');
		$content = '[{}]'; // fallback
		
		if($bannerSettings = $redis->get('bannerSetting'))
		{
			$content = $bannerSettings;
		}
		else
		{
			$banners = Banner::select('type', 'message as text', 'dismissable')
						->get();
			
			$response = $banners->toJson();
			
			$redis->put('bannerSetting', $response, now()->addMinutes(5));
			
			$content = $response;
		}
		
		return response($content)
				->header('Content-Type', 'application/json');
    }

}
