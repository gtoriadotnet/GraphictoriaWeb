<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Helpers\GridHelper;
use App\Http\Controllers\Controller;
use App\Grid\SoapService;

class TestController extends Controller
{
	/**
	 * @return Response
	 */
	public function generateThumbnail()
	{
		// mrgrey = https://www.roblox.com/asset/?id=1785197
		
		$test = new SoapService('Thumbnail');
		$result = $test->OpenJob(GridHelper::JobTemplate('test', 10, 0, 0, 'test render', 'place', ['http://www.roblox.com/asset/?id=444204653', 'PNG', 1920, 1080, 'https://www.gtoria.local/', 169618721]));
		
		return response(base64_decode($result->OpenJobExResult->LuaValue[0]->value))
				->header('Content-Type', 'image/png');
	}
}