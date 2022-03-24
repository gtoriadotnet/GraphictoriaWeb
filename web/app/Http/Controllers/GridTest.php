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
		
		local p = game:GetService("Players"):CreateLocalPlayer(0)
		p.CharacterAppearance = "http://api.gtoria.net/user/getCharacter.php?key=D869593BF742A42F79915993EF1DB&mode=ch&sid=1&uid=15"
		p:LoadCharacter(false)
		
		return game:GetService("ThumbnailGenerator"):Click("PNG", 2048, 2048, true, false)
TestScript;
		
		$test = new SoapService('http://192.168.0.3:64989');
		$result = $test->OpenJob(SoapService::MakeJobJSON('test', 10, 0, 0, 'test render', $testScript));
		
		return response(base64_decode($result->OpenJobExResult->LuaValue[0]->value))
				->header('Content-Type', 'image/png');
	}
}
