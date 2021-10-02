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
		
        return response()->json(['available' => $status->operational])
				->header('Access-Control-Allow-Origin', env('APP_URL'))
				->header('Vary', 'origin')
				->header('Content-Type', 'application/json');
	}
}
