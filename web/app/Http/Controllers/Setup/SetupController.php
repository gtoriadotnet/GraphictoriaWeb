<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\DynamicWebConfiguration;

class SetupController extends Controller
{
    public function getFile(Request $request, $file)
	{
		$file = basename($file);
		$filePath = Storage::path('setup/' . $file);
		
		if(!file_exists($filePath) || strtolower($file) == '.gitignore' || str_ends_with(strtolower($file), 'pdb.zip'))
			return response('404 not found.', 404)
				->header('Content-Type', 'text/plain');
		
		return response()->file($filePath);
	}
	
	public function getClientVersion()
	{
		return response(DynamicWebConfiguration::where('name', 'ClientUploadVersion')->first()->value)
				->header('Content-Type', 'text/plain');
	}
	
	public function getStudioVersion()
	{
		return response(DynamicWebConfiguration::where('name', 'StudioUploadVersion')->first()->value)
				->header('Content-Type', 'text/plain');
	}
}
