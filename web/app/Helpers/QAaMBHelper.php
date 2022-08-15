<?php

/*
	Graphictoria 2022
	Quick Administration and Management Bar helper
*/

namespace App\Helpers;

class QAaMBHelper
{
	public static function wmiWBemLocatorQuery($query)
	{
		if(class_exists('\\COM')) {
			try {
				$WbemLocator = new \COM( "WbemScripting.SWbemLocator" );
				$WbemServices = $WbemLocator->ConnectServer( '127.0.0.1', 'root\CIMV2' );
				$WbemServices->Security_->ImpersonationLevel = 3;
				return $WbemServices->ExecQuery( $query );
			} catch ( \com_exception $e ) {
				echo $e->getMessage();
			}
		} elseif ( ! extension_loaded( 'com_dotnet' ) )
			trigger_error( 'It seems that the COM is not enabled in your php.ini', E_USER_WARNING );
		else {
			$err = error_get_last();
			trigger_error( $err['message'], E_USER_WARNING );
		}

		return false;
	}

	public static function getSystemMemoryInfo($output_key = '')
	{
		return cache()->remember('QAaMB-Memory-Info', 5, function(){
			$keys = array('MemTotal', 'MemFree', 'MemAvailable', 'SwapTotal', 'SwapFree');
			$result = array();

			try {
				if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
					// LINUX
					
					$proc_dir = '/proc/';
					$data = _dir_in_allowed_path( $proc_dir ) ? @file( $proc_dir . 'meminfo' ) : false;
					if ( is_array( $data ) )
						foreach ( $data as $d ) {
							if ( 0 == strlen( trim( $d ) ) )
								continue;
							$d = preg_split( '/:/', $d );
							$key = trim( $d[0] );
							if ( ! in_array( $key, $keys ) )
								continue;
							$value = 1000 * floatval( trim( str_replace( ' kB', '', $d[1] ) ) );
							$result[$key] = $value;
						}
				} else {
					// WINDOWS
					
					$wmi_found = false;
					if ( $wmi_query = self::wmiWBemLocatorQuery( 
						"SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem" ) ) {
						foreach($wmi_query as $r) {
							$result['MemFree'] = $r->FreePhysicalMemory * 1024;
							$result['MemAvailable'] = $r->FreeVirtualMemory * 1024;
							$result['SwapFree'] = $r->TotalSwapSpaceSize * 1024;
							$result['SwapTotal'] = $r->TotalVirtualMemorySize * 1024;
							$result['MemTotal'] = $r->TotalVisibleMemorySize * 1024;
							$wmi_found = true;
						}
					}
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return empty($output_key) || !isset($result[$output_key]) ? $result : $result[$output_key];
		});
	}
	
	public static function getSystemCpuInfo($output_key = '')
	{
		return cache()->remember('QAaMB-Cpu-Info', 5, function(){
			$result = 0;

			try {
				if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
					// LINUX
					
					return sys_getloadavg();
				} else {
					// WINDOWS
					
					$wmi_found = false;
					if ( $wmi_query = self::wmiWBemLocatorQuery( 
						"SELECT LoadPercentage FROM Win32_Processor" ) ) {
						foreach($wmi_query as $r) {
							$result = $r->LoadPercentage / 100;
							$wmi_found = true;
						}
					}
				}
			} catch(Exception $e) {
				echo $e->getMessage();
			}
			return empty($output_key) || !isset($result[$output_key]) ? $result : $result[$output_key];
		});
	}
	
	public static function memoryString($memory)
	{
		$unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		return @round($memory/pow(1024,($i=floor(log($memory,1024)))),2).' '.$unit[$i];
	}
	
	public static function getMemoryUsage()
	{
		$memoryInfo = self::getSystemMemoryInfo();
		
		// XlXi: the -2 is required so it fits inside of the bar thing
		// XlXi: change this if theres ever a separate graph
		return sprintf(
			'%s of %s (%s%%) <br/> %s Free',
			self::memoryString($memoryInfo['MemTotal'] - $memoryInfo['MemFree']),
			self::memoryString($memoryInfo['MemTotal']),
			round(self::getMemoryPercentage() * (100-2)),
			self::memoryString($memoryInfo['MemFree'])
		);
	}
	
	public static function getMemoryPercentage()
	{
		$memoryInfo = self::getSystemMemoryInfo();
		
		return ($memoryInfo['MemTotal'] - $memoryInfo['MemFree']) / $memoryInfo['MemTotal'];
	}
	
	public static function getCpuUsage()
	{
		// XlXi: the -2 is required so it fits inside of the bar thing
		// XlXi: change this if theres ever a separate graph
		return sprintf(
			'%s%% CPU Usage',
			round(self::getSystemCpuInfo() * (100-2))
		);
	}
}
