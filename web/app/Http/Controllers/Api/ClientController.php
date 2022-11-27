<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\NegotiationTicket;

class ClientController extends Controller
{
	function generateAuthTicket()
	{
		$ticket = Str::random(100);
		
		NegotiationTicket::create([
			'ticket' => $ticket,
			'userId' => Auth::user()->id
		]);
		
		return response($ticket)
				->header('Content-Type', 'text/plain');
	}
}
