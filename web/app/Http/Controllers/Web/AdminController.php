<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	// Moderator+
	function dashboard()
	{
		//
	}
	
	// Admin+
    function arbiterDiag(Request $request, string $arbiterType = null)
	{
		if($arbiterType == null)
			return view('web.admin.diag.picker');
		
		return view('web.admin.diag')->with([
			'title' => sprintf('%s Arbiter Diag', $arbiterType),
			'arbiter' => $arbiterType
		]);
	}
}
