<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Games;
use App\Models\WebStatus;

class GamesController extends Controller
{
    /**
     * Returns if the games arbiter is operational or not.
     *
     * @return Response
     */
    public function isAvailable()
    {
		$status = WebStatus::where('name', 'GamesArbiter')
					->first();
		
      if (!$status) return response()->json(['error' => false])
				->header('Content-Type', 'application/json');

        return response()->json(['available' => $status->operational])
				->header('Content-Type', 'application/json');
	}
	
	public function validatePlaceJoin()
	{
		// todo: move to backend and make this actually return if the player is validated
		//       this is only here for testing
		
		return response('true', null)
				->header('Content-Type', 'text/plain');
	}
}
