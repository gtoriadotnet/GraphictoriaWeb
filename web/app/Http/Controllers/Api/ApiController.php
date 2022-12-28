<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
	{
		// TODO: XlXi: Add some checks here, such as pinging api.virtubrick.net, checking commonly used API functions, etc...
		return response('API OK!')
					->header('Content-Type', 'text/plain');
	}
}
