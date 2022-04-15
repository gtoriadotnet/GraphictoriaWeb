<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;;
use App\Http\Controllers\Controller;

class AssetGame extends Controller
{
	public function machineConfiguration()
	{
		// todo: move to backend
		// this is only here for testing
		
		return response('')
				->header('Content-Type', 'text/plain');
	}
	
	public function validateMachine()
	{
		// todo: move to backend and make this actually return if the player is validated
		//       this is only here for testing
		
		// true = machine banned
		// false = machine is ok
		return response(json_encode(['success'=>false]))
				->header('Content-Type', 'text/plain');
	}
}
