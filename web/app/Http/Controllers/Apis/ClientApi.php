<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;;
use App\Http\Controllers\Controller;

class ClientApi extends Controller
{
	public function validatePlaceJoin()
	{
		// todo: move to backend and make this actually return if the player is validated
		//       this is only here for testing
		
		return response('true')
				->header('Content-Type', 'text/plain');
	}
}
