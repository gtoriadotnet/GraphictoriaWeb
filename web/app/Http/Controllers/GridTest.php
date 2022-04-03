<?php

namespace App\Http\Controllers;

use App\Grid\SoapService;
use Illuminate\Http\Request;

class GridTest extends Controller
{
	/**
	 * @return Response
	 */
	public function generateThumbnail()
	{
		$testScript = <<<TestScript
		settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;
		game:GetService("ContentProvider"):SetThreadPool(16)
		game:GetService("Stats"):SetReportUrl("http://api.gtoria.net/teststat")
		
		game:GetService("ContentProvider"):SetBaseUrl("http://www.roblox.com/")
		game:LoadWorld(23173663)
		
		return game:GetService("ThumbnailGenerator"):Click("PNG", 1920, 1080, false, false)
TestScript;
		
		$test = new SoapService('http://127.0.0.1:64989');
		$result = $test->OpenJob(SoapService::MakeJobJSON('test', 10, 0, 0, 'test render', $testScript));
		
		return response(base64_decode($result->OpenJobExResult->LuaValue[0]->value))
				->header('Content-Type', 'image/png');
	}
}
