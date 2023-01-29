<?php

/*
	XlXi 2022
	Grid helper
*/

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\DynamicWebConfiguration;

class GridHelper
{
	public static function isIpWhitelisted()
	{
		$ip = request()->ip();
		$whitelistedIps = explode(';', DynamicWebConfiguration::where('name', 'WhitelistedIPs')->first()->value);
		
		return in_array($ip, $whitelistedIps);
	}
	
	public static function isAccessKeyValid()
	{
		$accessKey = DynamicWebConfiguration::where('name', 'ComputeServiceAccessKey')->first()->value;
		
		return (request()->header('AccessKey') == $accessKey);
	}
	
	public static function hasAllAccess()
	{
		if(app()->runningInConsole()) return true;
		if(self::isIpWhitelisted() && self::isAccessKeyValid()) return true;
		
		return false;
	}
	
	public static function LuaValue($value)
	{
		switch ($value) {
			case is_bool(json_encode($value)) || $value == 1:
				return json_encode($value);
			default:
				return $value;
		}
	}
	
	public static function CastType($value)
	{
		$luaTypeConversions = [
			'NULL' 		=> 'LUA_TNIL',
			'boolean'	=> 'LUA_TBOOLEAN',
			'integer'	=> 'LUA_TNUMBER',
			'double'	=> 'LUA_TNUMBER',
			'string'	=> 'LUA_TSTRING',
			'array'		=> 'LUA_TTABLE',
			'object'	=> 'LUA_TNIL'
		];
		return $luaTypeConversions[gettype($value)];
	}
	
	public static function ToLuaArguments($luaArguments = [])
    {
		$luaValue = ['LuaValue' => []];
		
		foreach ($luaArguments as $argument) {
			array_push(
				$luaValue['LuaValue'],
				[
					'type' => self::CastType($argument),
					'value' => self::LuaValue($argument)
				]
			);
		}
		
		return $luaValue;
    }
	
	public static function Job($jobID, $expiration, $category, $cores, $scriptName, $script, $scriptArgs = [])
	{
		return [
				'job' => [
					'id' => $jobID,
					'expirationInSeconds' => $expiration,
					'category' => $category,
					'cores' => $cores
				],
				'script' => [
					'name' => $scriptName,
					'script' => $script,
					'arguments' => self::ToLuaArguments($scriptArgs)
				]
			];
	}
	
	public static function JobTemplate($jobID, $expiration, $category, $cores, $scriptName, $templateName, $scriptArgs = [])
	{
		$disk = Storage::build([
			'driver' => 'local',
			'root' => storage_path('app/grid/scripts'),
		]);
		
		$fileName = sprintf('%s.lua', $templateName);
		
		if(!$disk->exists($fileName))
			throw new Exception('Unable to locate template file.');
		
		$job = self::Job($jobID, $expiration, $category, $cores, $scriptName, '', $scriptArgs);
		$job['script']['script'] = $disk->get($fileName);
		
		return $job;
	}
	
	private static function getThumbDisk()
	{
		return Storage::build([
			'driver' => 'local',
			'root' => storage_path('app/grid/thumbnails'),
		]);
	}
	
	public static function getDefaultThumbnail($fileName)
	{
		$disk = self::getThumbDisk();
		
		if(!$disk->exists($fileName))
			throw new Exception('Unable to locate template file.');
		
		return $disk->get($fileName);
	}
	
	public static function getUnknownThumbnail()
	{
		return self::getDefaultThumbnail('UnknownThumbnail.png');
	}
	
	private static function getGameDisk()
	{
		return Storage::build([
			'driver' => 'local',
			'root' => storage_path('app/grid/game'),
		]);
	}
	
	private static function getXMLFromGameDisk($fileName)
	{
		$disk = self::getGameDisk();
		
		if(!$disk->exists($fileName))
			throw new Exception('Unable to locate template file.');
		
		return $disk->get($fileName);
	}
	
	public static function getBodyColorsXML()
	{
		return self::getXMLFromGameDisk('BodyColors.xml');
	}
	
	public static function getBodyPartXML()
	{
		return self::getXMLFromGameDisk('BodyPart.xml');
	}
	
	public static function getFaceXML()
	{
		return self::getXMLFromGameDisk('Face.xml');
	}
	
	public static function getArbiter($name)
	{
		$query = DynamicWebConfiguration::where('name', sprintf('%sArbiterIP', $name))->first();
		if(!$query)
			throw new Exception('Unknown arbiter.');
		
		return $query->value;
	}
	
	public static function gameArbiter()
	{
		return sprintf('http://%s:64989', self::getArbiter('Game'));
	}
	
	public static function thumbnailArbiter()
	{
		return sprintf('http://%s:64989', self::getArbiter('Thumbnail'));
	}
	
	public static function gameArbiterMonitor()
	{
		return sprintf('http://%s:64990', self::getArbiter('Game'));
	}
	
	public static function thumbnailArbiterMonitor()
	{
		return sprintf('http://%s:64990', self::getArbiter('Thumbnail'));
	}
}
