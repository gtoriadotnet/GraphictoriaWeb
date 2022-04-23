<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Cookie;

use App\Models\WebsiteConfiguration;

class MaintenanceController extends Controller
{
	public function showPage()
	{
		$data = json_decode(file_get_contents(storage_path('framework/down')), true);
		
		return view('maintenance', ['hideLogin' => !isset($data['secret'])]);
	}
	
	/**
     * Handles the maintenance bypass request.
     *
     * @return Response
     */
	public function bypass(Request $request)
	{
		$password = $request->input('password');
		$buttons = $request->input('buttons');
		
		if(!$password || !$buttons) {
			return response('{"errors":[{"code":400,"message":"BadRequest"}]}', 400)
					->header('Cache-Control', 'private')
					->header('Content-Type', 'application/json; charset=utf-8');
		}
		
		/* */
		
		$mtconf = json_decode(WebsiteConfiguration::whereName('MaintenancePassword')->first()->value);
		
		if(file_exists(storage_path('framework/down')) && $password == $mtconf->password)
		{
			$btns = array_slice($buttons, -count($mtconf->combination));
			$data = json_decode(file_get_contents(storage_path('framework/down')), true);
			
			if(isset($data['secret']) && $btns === $mtconf->combination)
			{
				$trustedHosts = explode(',', env('TRUSTED_HOSTS'));
				$origin = join('.', array_slice(explode('.', explode('//', $request->headers->get('origin'))[1]), -2));
				$passCheck = false;
				
				foreach($trustedHosts as &$host)
				{
					if(str_ends_with($origin, $host))
						$passCheck = true;
				}
				
				
				$expiresAt = Carbon::now()->addHours(24);
				$bypassCookie = new Cookie('gt_constraint', base64_encode(json_encode([
					'expires_at' => $expiresAt->getTimestamp(),
					'mac' => hash_hmac('SHA256', $expiresAt->getTimestamp(), $data['secret']),
				])), $expiresAt);
				$bypassCookie = $bypassCookie->withSameSite('none');
				
				if($passCheck)
					$bypassCookie = $bypassCookie->withDomain('.' . $origin);
				
				return response('')
						->withCookie($bypassCookie);
			}
		}
		
		return response('', 403);
	}
}
