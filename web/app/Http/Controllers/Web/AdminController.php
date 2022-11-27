<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\DynamicWebConfiguration;

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
	function dashboard()
	{
		return view('web.admin.dashboard');
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
