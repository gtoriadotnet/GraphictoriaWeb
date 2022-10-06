<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function home()
	{
		return view('blog.home');
	}
}
