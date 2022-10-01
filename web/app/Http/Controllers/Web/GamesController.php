<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function index()
	{
		return view('web.games.index');
	}
}
