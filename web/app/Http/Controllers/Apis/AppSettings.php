<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Helpers\JSON;
use App\Helpers\GridHelper;
use App\Helpers\ErrorHelper;

use App\Http\Controllers\Controller;

use App\Models\FFlag;
use App\Models\Fbucket;

class AppSettings extends Controller
{
	/**
	 * A list of flag prefixes
	 *
	 * @var array
	 */
	protected $prefixes = [
		'Unscoped' => '',
		'Fast' => 'F',
		'Dynamic' => 'DF',
		'Synchronised' => 'SF'
	];
	
	/**
	 * A list of flag types
	 *
	 * @var array
	 */
	protected $types = [
		'Log' => 'Log',
		'Int' => 'Int',
		'String' => 'String',
		'Boolean' => 'Flag'
	];
	
    /**
     * Returns a JSON array of settings for the specified bucket.
     *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string                    $bucketName
     * @return Response
     */
    public function getBucket(Request $request, $bucketName)
    {
		$primaryBucket = Fbucket::where('name', $bucketName);
		
		if($primaryBucket->exists()) {
			$primaryBucket = $primaryBucket->first();
			
			$bucketIds = [ $primaryBucket->id ];
			$bucketIds = array_merge($bucketIds, json_decode($primaryBucket->inheritedGroupIds));
			
			if($primaryBucket->protected == 1 && !GridHelper::hasAllAccess($request)) {
				return ErrorHelper::error([
					'code' => 2,
					'message' => 'You do not have access to this bucket.'
				], 401);
			}
			
			/* */
			
			$flags = [];
			
			foreach($bucketIds as $bucket) {
				$fflags = FFlag::where('bucketId', $bucket)->get();
				
				foreach($fflags as $flag) {
					$prefix = $this->prefixes[$flag->type];
					$dataType = $this->types[$flag->dataType];
					
					$name = '';
					if($flag->type != 'Unscoped') {
						$name = ($prefix . $dataType);
					}
					
					$name .= $flag->name;
					
					$flags[$name] = $flag->value;
				}
			}
			
			ksort($flags);
			
			return JSON::EncodeResponse($flags);
		} else {
			return ErrorHelper::error([
				'code' => 1,
				'message' => 'The requested bucket does not exist.'
			]);
		}
	}

}
