<?php

/*
	Graphictoria 2022
	Domain helper.
*/

namespace App\Helpers;

use Illuminate\Http\Request;

class DomainHelper
{
	public static function TopLevelDomain()
	{
		$baseurl = config('app.url');
		$baseurl = str_replace(['http://', 'https://'], '', $baseurl);
		
		return $baseurl;
	}
	
	public static function DotLeadTopLevelDomain()
	{
		return '.' . DomainHelper::TopLevelDomain();
	}
}
