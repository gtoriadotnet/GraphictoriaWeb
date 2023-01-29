<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\AdminUpload;
use App\Models\DynamicWebConfiguration;
use App\Models\PunishmentType;
use App\Models\Username;
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
		$user = User::where('id', $request->get('ID'));
		if(!$user->exists())
			abort(400);
		
		return view('web.admin.useradmin')->with('user', $user->first());
	}
	
	// GET admin.manualmoderateuser
	function manualModerateUser(Request $request)
	{
		$user = User::where('id', $request->get('ID'));
		if(!$user->exists())
			abort(400);
		
		return view('web.admin.manualmoderateuser')->with('user', $user->first());
	}
	
	// POST admin.manualmoderateusersubmit
	function manualModerateUserSubmit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'ID' => [
				'required',
				Rule::exists('App\Models\User', 'id')
			],
			'moderate-action' => [
				'required',
				Rule::exists('App\Models\PunishmentType', 'id')
			],
			'internal-note' => 'required'
		], [
			'moderate-action.required' => 'Please provide an account state.',
			'internal-note.required' => 'An internal note must be provided on why this user\'s state was changed.'
		]);
		
		if($validator->fails())
			return $this->manualModerateUserError($validator);
		
		$user = User::where('id', $request->get('ID'))->first();
		
		if(Auth::user()->id == $user->id)
		{
			$validator->errors()->add('ID', 'Cannot apply account state to current user.');
			return $this->manualModerateUserError($validator);
		}
		
		if(
			($user->hasRoleset('ProtectedUser') && !Auth::user()->hasRoleset('Owner'))
			
			// XlXi: Prevent lower-ranks from banning higher ranks.
			|| (
				($user->hasRoleset('Owner') && !Auth::user()->hasRoleset('Owner'))
				&& ($user->hasRoleset('Administrator') && !Auth::user()->hasRoleset('Administrator'))
			)
		)
		{
			$validator->errors()->add('ID', 'User is protected. Contact an owner.');
			return $this->manualModerateUserError($validator);
		}
		
		// XlXi: Moderation action type 1 is None.
		if($request->get('moderate-action') == 1 && !$user->hasActivePunishment())
			return $this->manualModerateUserSuccess(sprintf('%s already has an account state of None. No changes applied.', $user->username));
		
		if($request->get('moderate-action') != 1 && $user->hasActivePunishment())
		{
			$validator->errors()->add('ID', 'User already has an active punishment.');
			return $this->manualModerateUserError($validator);
		}
		
		if(Auth::user()->hasRoleset('Administrator'))
		{
			if($request->has('scrub-username'))
			{
				$newUsername = sprintf('[ Content Deleted %d ]', $user->id);
				
				Username::where('user_id', $user->id)
						->update([
							'scrubbed' => true,
							'scrubbed_by' => Auth::user()->id
						]);
				
				Username::create([
					'username' => $newUsername,
					'user_id' => $user->id
				]);
				
				$user->username = $newUsername;
				$user->save();
			}
		}
		
		PunishmentType::where('id', $request->get('moderate-action'))
						->first()
						->applyToUser([
							'user_id' => $user->id,
							'user_note' => $request->get('user-note') ?: '',
							'internal_note' => $request->get('internal-note') ?: '',
							'moderator_id' => Auth::user()->id
						]);
		
		return $this->manualModerateUserSuccess(sprintf('Successfully applied account state to %s.', $user->username));
	}
	
	function manualModerateUserError($validator)
	{
		$user = User::where('id', request()->get('ID'))->first();
		
		return view('web.admin.manualmoderateuser')
				->with('user', $user)
				->withErrors($validator);
	}
	
	function manualModerateUserSuccess($message)
	{
		$user = User::where('id', request()->get('ID'))->first();
		
		return view('web.admin.manualmoderateuser')
				->with('user', $user)
				->with('success', $message);
	}
	
	// GET admin.usersearch
	function userSearch(Request $request)
	{
		$types = [
			'userid' => 'UserId',
			'username' => 'UserName',
			'emailaddress' => 'EmailAddress',
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
		$users = User::where('id', $request->get('userid'))
						->paginate(25)
						->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users);
	}
	
	function userSearchQueryUserName(Request $request)
	{
		$users = User::where('username', 'like', '%' . $request->get('username') . '%')
						->paginate(25)
						->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users);
	}
	
	function userSearchQueryEmailAddress(Request $request)
	{
		if(!Auth::user()->hasRoleset('Owner'))
			abort(403);
		
		$users = User::where('email', $request->get('emailaddress'))
						->paginate(25)
						->appends($request->all());
		
		return view('web.admin.usersearch')->with('users', $users);
	}
	
	function userSearchQueryIpAddress(Request $request)
	{
		if(!Auth::user()->hasRoleset('Owner'))
			abort(403);
		
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
	
	// Admin+
	
	// GET admin.autoupload
	function autoUpload()
	{
		return view('web.admin.catalog.autoupload');
	}
	
	// GET admin.assetupload
	function assetUpload()
	{
		return view('web.admin.catalog.assetupload');
	}
	
	// GET admin.adminuploads
	function getAdminUploads(Request $request)
	{
		$uploads = AdminUpload::query()
								->orderByDesc('id')
								->paginate(25);
		
		return view('web.admin.catalog.adminuploads')->with('uploads', $uploads);
	}
	
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
