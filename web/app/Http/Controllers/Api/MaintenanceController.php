<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\DynamicWebConfiguration;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;

class MaintenanceController extends Controller
{
	public function bypass(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'password' => ['required'],
			'buttons' => ['required']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$password = $valid['password'];
		$buttons = $valid['buttons'];
		
		$mtconf = json_decode(DynamicWebConfiguration::whereName('MaintenancePassword')->first()->value);
		
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
				$bypassCookie = new Cookie('vb_constraint', base64_encode(json_encode([
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
		
		$validator->errors()->add('password', 'Bad Request.');
		return ValidationHelper::generateValidatorError($validator);
	}
}
