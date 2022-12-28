<?php

/*
	XlXi 2022
	This file handles communications between the arbiter and the website.
*/

namespace App\Grid;

use Illuminate\Support\Facades\Storage;
use SoapClient;

use App\Models\ArbiterSoapFault;
use App\Helpers\GridHelper;

class SoapService
{
	/**
     * SoapClient used by the functions in this class.
     *
     * @var SoapClient
     */
	public $Client;
	
	/**
     * Constructs the SoapClient.
	 *
	 * Arbiter address should be formatted like "http://127.0.0.1:64989"
     *
     * @param  string  $arbiterAddr
     * @return null
     */
	public function __construct($arbiterType) {
		$arbiter = GridHelper::{strtolower($arbiterType) . 'Arbiter'}();
		$this->Client = new SoapClient(
			Storage::path('grid/RCCService.wsdl'), // Arbiter WCF service WSDL should not be used for RCCService calls.
			[
				'location' => $arbiter,
				'uri' => 'http://roblox.com/',
				'exceptions' => false
			]
		);
	}
	
	/**
     * Calls on the soap service.
     *
     * @param  string  $name
	 * @param  array $args
     * @return null
     */
	public function CallService($name, $args = []) {
		$soapResult = $this->Client->{$name}($args);
		
		if(is_soap_fault($soapResult)) {
			ArbiterSoapFault::create([
				'function' => $name,
				'code' => $soapResult->getCode(),
				'message' => $soapResult->getMessage(),
				'job_arguments' => json_encode($args)
			]);
		}
		
		return $soapResult;
	}
	
	/* Service functions */
	
	public function HelloWorld()
    {
		return $this->CallService('HelloWorld');
    }
	
	public function GetVersion()
    {
		return $this->CallService('GetVersion');
    }
	
	public function GetStatus()
    {
		return $this->CallService('GetStatus');
    }
	
	/* Job specific functions */
	
	public function BatchJobEx($args)
    {
		return $this->CallService('BatchJobEx', $args);
    }
	
	public function OpenJobEx($args)
    {
		return $this->CallService('OpenJobEx', $args);
    }
	
	public function ExecuteEx($args)
    {
		return $this->CallService('ExecuteEx', $args);
    }
	
	public function GetAllJobsEx()
    {
		return $this->CallService('GetAllJobsEx');
    }
	
	public function CloseExpiredJobs()
    {
		return $this->CallService('CloseExpiredJobs');
    }
	
	public function CloseAllJobs()
    {
		return $this->CallService('CloseAllJobs');
    }
	
	/* Job management */
	
	public function DiagEx($jobID, $type)
    {
		return $this->CallService(
			'DiagEx',
			[
				'type' => $type,
				'jobID' => $jobID
			]
		);
    }
	
	public function CloseJob($jobID)
    {
		return $this->CallService(
			'CloseJob',
			[
				'jobID' => $jobID
			]
		);
    }
	
	public function GetExpiration($jobID)
    {
		return $this->CallService(
			'GetExpiration',
			[
				'jobID' => $jobID
			]
		);
    }
	
	public function RenewLease($jobID, $expiration)
    {
		return $this->CallService(
			'RenewLease',
			[
				'jobID' => $jobID,
				'expirationInSeconds' => $expiration
			]
		);
    }
	
	/* dep */
	
	public function BatchJob($args)
    {
		return $this->BatchJobEx($args);
    }
	
	public function OpenJob($args)
    {
		return $this->OpenJobEx($args);
    }
	
	public function Execute($args)
    {
		return $this->ExecuteEx($args);
    }
	
	public function GetAllJobs()
    {
		return $this->GetAllJobsEx();
    }
	
	public function Diag($jobID, $type)
    {
		return $this->DiagEx($jobID, $type);
	}
}
