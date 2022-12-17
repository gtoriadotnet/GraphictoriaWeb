<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Matching\ValidatorInterface;

class CaseInsensitiveUriValidator implements ValidatorInterface
{
	public function matches(Route $route, Request $request)
	{
		$path = rtrim($request->getPathInfo(), '/') ?: '/';
		
		return preg_match($route->getCompiled()->getRegex() . 'i', rawurldecode($path));
	}
}
