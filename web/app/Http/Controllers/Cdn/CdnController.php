<?php

namespace App\Http\Controllers\Cdn;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\CdnHash;
use App\Helpers\CdnHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;

class CdnController extends Controller
{
    public function getContent(Request $request, $hash)
	{
		$disk = CdnHelper::GetDisk();
		
		if(preg_match('/^[a-f0-9]{64}$/i', $hash) && $disk->exists($hash)) {
			$content = CdnHash::where('hash', $hash)->first();
			
			if(!$content || $content->deleted)
				return response('This item is currently unavailable.')
							->header('content-type', 'text/plain');
			
			return response($disk->get($hash))
						->header('content-type', $content->mime_type);
		} else {
			return response('Invalid hash.')
					->header('content-type', 'text/plain');
		}
	}
}
