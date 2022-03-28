<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\AuthHelper;

class UserController extends Controller
{
	/**
     * Gets the current user's settings.
     *
     * @return Response
     */
    public function GetSettings(Request $request) {
		$currentUser = AuthHelper::GetCurrentUser($request);
		
		if($currentUser) {
			return Response()->json([
				'data' => $currentUser
			]);
		} else {
			return Response()->json([
				'error' => 'Unauthorized',
				'userFacingMessage' => 'You are not authorized to perform this request.'
			]);
		}
		
		// Not sure how we'd get here, but just in case
		return Response(null, 400);
	}
}
