<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function landing()
    {
        return view('web.home.landing');
    }
	
	public function dashboard()
    {
        return view('web.home.dashboard');
    }
}
