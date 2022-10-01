<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\DynamicWebConfiguration;

class AdminController extends Controller
{
	// Moderator+
	function dashboard()
	{
		return view('web.admin.dashboard');
	}
	
	// Admin+
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
}
