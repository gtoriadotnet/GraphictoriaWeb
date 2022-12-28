<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
	{
		$data = json_decode(file_get_contents(storage_path('framework/down')), true);
		
		return view('web.maintenance', ['hideLogin' => !isset($data['secret'])]);
	}
}
