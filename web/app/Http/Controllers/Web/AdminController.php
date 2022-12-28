<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\DynamicWebConfiguration;
use App\Models\User;
use App\Models\UserIp;

class AdminController extends Controller
{
	function getJs(Request $request, string $jsFile)
	{
		$filePath = public_path('js/adm/' . basename($jsFile));
		
		if(!file_exists($filePath))
			abort(404);
		
		return response()->file($filePath);
	}
	
	// Moderator+
	
	// GET admin.dashboard
	function dashboard()
	{
		return view('web.admin.dashboard');
	}
	
	// GET admin.usersearch
	function userSearch()
	{
		return view('web.admin.usersearch');
	}
	
	// POST admin.usersearch
	function userSearchQuery(Request $request)
	{
		if($request->has('userid-button'))
		{
			$request->validate([
				'userid-search' => ['required', 'int']
			]);
			return view('web.admin.usersearch')->with('users', User::where('id', $request->get('userid-search'))->get());
		}
		
		if($request->has('username-button'))
		{
			$request->validate([
				'username-search' => ['required', 'string']
			]);
			return view('web.admin.usersearch')->with('users', User::where('username', 'like', '%' . $request->get('username-search') . '%')->get());
		}
		
		if($request->has('ipaddress-button'))
		{
			$request->validate([
				'ipaddress-search' => ['required', 'ip']
			]);
			
			$result = UserIp::where('ipAddress', $request->get('ipaddress-search'))
						->join('users', 'users.id', '=', 'user_ips.userId')
						->orderBy('users.id', 'desc');
			
			return view('web.admin.usersearch')->with('users', $result->get())->with('isIpSearch', true);
		}
		
		return view('web.admin.usersearch')->with('error', 'Input validation failed.');
	}
	
	// GET admin.autoupload
	function autoUpload()
	{
		return view('web.admin.autoupload');
	}
	
	// Admin+
	function metricsVisualization()
	{
		return view('web.admin.metricsvisualization');
	}
	
    function arbiterDiag(Request $request, string $arbiterType = null)
	{
		return view('web.admin.arbiter.diag')->with([
			'title' => sprintf('%s Arbiter Diag', $arbiterType),
			'arbiter' => $arbiterType
		]);
	}
	
	// Owner+
	function arbiterManagement(Request $request, string $arbiterType = null, string $jobId = null)
	{
		return view('web.admin.arbiter.management')->with([
			'title' => sprintf('%s Arbiter Management', $arbiterType),
			'arbiter' => $arbiterType
		]);
	}
	
	function configuration(Request $request)
	{
		return view('web.admin.configuration')->with([
			'values' => DynamicWebConfiguration::get()
		]);
	}
	
	function deployer()
	{
		return view('web.admin.deployer');
	}
}
