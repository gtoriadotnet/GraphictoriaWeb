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
	
	// GET admin.useradmin
	function userAdmin(Request $request)
	{
		$request->validate([
			'ID' => ['required', 'int', 'exists:users,id']
		]);
		
		$user = User::where('id', $request->get('ID'))->first();
		return view('web.admin.useradmin')->with('user', $user);
	}
	
	// GET admin.usersearch
	function userSearch(Request $request)
	{
		$types = [
			'userid' => 'UserId',
			'username' => 'UserName',
			'ipaddress' => 'IpAddress'
		];
		
		foreach($types as $type => &$func)
		{
			if($type == $request->has($type))
				return $this->{'userSearchQuery' . $func}($request);
		}
		
		return view('web.admin.usersearch');
	}
	
	// POST admin.usersearchquery
	function userSearchQueryUserId(Request $request)
	{
		$request->validate([
			'userid' => ['required', 'int']
		]);
		
		$users = User::where('id', $request->get('userid'))
						->paginate(25)
						->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users);
	}
	
	function userSearchQueryUserName(Request $request)
	{
		$request->validate([
			'username' => ['required', 'string']
		]);
		
		$users = User::where('username', 'like', '%' . $request->get('username') . '%')
						->paginate(25)
						->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users);
	}
	
	function userSearchQueryIpAddress(Request $request)
	{
		$request->validate([
			'ipaddress' => ['required', 'ip']
		]);
		
		$users = UserIp::where('ipAddress', $request->get('ipaddress'))
					->join('users', 'users.id', '=', 'user_ips.userId')
					->orderBy('users.id', 'desc')
					->paginate(25)
					->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users)->with('isIpSearch', true);
	}
	
	// GET admin.userlookup
	function userLookup()
	{
		return view('web.admin.userlookup');
	}
	
	// POST admin.userlookupquery
	function userLookupQuery(Request $request)
	{
		$request->validate([
			'lookup' => ['required', 'string']
		]);
		
		$users = [];
		
		foreach(preg_split('/\r\n|\r|\n/', $request->get('lookup')) as $username)
		{
			$user = User::where('username', $username);
			
			if($user->exists())
			{
				$user = $user->first();
				array_push(
					$users,
					[
						'found' => true,
						'user' => $user
					]
				);
			}
			else
			{
				array_push(
					$users,
					[
						'found' => false,
						'username' => $username
					]
				);
			}
		}
		
		return view('web.admin.userlookup')->with('users', $users)->with('input', $request->get('lookup'));
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
