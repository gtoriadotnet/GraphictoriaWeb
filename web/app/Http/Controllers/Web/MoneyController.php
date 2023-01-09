<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyController extends Controller
{
    public function transactions()
	{
		return view('web.money.transactions');
	}
}
