<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Grid\SoapService;

class TestController extends Controller
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
		
		local p = game:GetService("Players"):CreateLocalPlayer(0)
		p.CharacterAppearance = "http://api.gtoria.net/user/getCharacter.php?key=D869593BF742A42F79915993EF1DB&mode=ch&sid=1&uid=1"
		p:LoadCharacter(false)
		
		return game:GetService("ThumbnailGenerator"):Click("PNG", 420, 420, true, false)
TestScript;
		
		$test = new SoapService('http://192.168.0.3:64989');
		$result = $test->OpenJob(SoapService::MakeJobJSON('test', 10, 0, 0, 'test render', $testScript));
		
		return response(base64_decode($result->OpenJobExResult->LuaValue[0]->value))
				->header('Content-Type', 'image/png');
	}
}