<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestSiteController extends Controller
{
    public function info()
	{
		return view('web.testing.info');
	}
}
