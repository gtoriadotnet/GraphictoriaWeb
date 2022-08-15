<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use App\Grid\SoapService;
use App\Helpers\CdnHelper;
use App\Models\RenderTracker;

class ArbiterRender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;
	
	/**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

	/**
     * The tracker instance.
     *
     * @var \App\Models\RenderTracker
     */
	public $tracker;
	
	/**
     * Is the render 3d?
     *
     * @var bool
     */
	public $is3D;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RenderTracker $tracker, bool $is3D)
    {
        $this->tracker = $tracker;
        $this->is3D = $is3D;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $testScript = <<<TestScript
		settings()["Task Scheduler"].ThreadPoolConfig = Enum.ThreadPoolConfig.PerCore4;
		game:GetService("ContentProvider"):SetThreadPool(16)
		game:GetService("Stats"):SetReportUrl("http://api.gtoria.net/reportstat?cock=1")

		local Lighting = game:GetService("Lighting")
		Lighting.ClockTime = 13
		Lighting.GeographicLatitude = -5
		
		game:Load("http://gtoria.net/asset/?id=3529");
		for _, Object in pairs(game:GetChildren())do
			if Object:IsA("Tool") then
				Object.Parent = workspace
			end
		end

		--                                                 format,  width,  height,  sky,   crop
		return game:GetService("ThumbnailGenerator"):Click("OBJ",   840,    840,     true,  true)
TestScript;
		
		$test = new SoapService('http://192.168.0.3:64989');
		$result = $test->OpenJob(SoapService::MakeJobJSON(Str::uuid()->toString(), 120, 0, 0, sprintf('Render %s %d', $this->tracker->type, $this->tracker->target), $testScript));
		
		if(is_soap_fault($result))
			$this->fail(sprintf('SOAP Fault: (faultcode: %s, faultstring: %s)', $result->faultcode, $result->faultstring));
		
		$result = $result->OpenJobExResult->LuaValue[0]->value;
		
		if($this->is3D) {
			$content = json_decode($result);
			$result = [
				'camera' => $content->camera,
				'AABB' => $content->AABB,
				'obj' => '',
				'mtl' => '',
				'textures' => []
			];
			
			$mtlTmp;
			foreach($content->files as $file => $fileB64) {
				$extension = strtolower(substr(strrchr($file, '.'), 1));
				if($extension == 'mtl')
					$mtlTmp = base64_decode($fileB64->content);
			}
			
			// RCC adds map_d for whatever reason. (alpha map)
			$mtlTmp = preg_replace('/^map_d.+\n/im', '', $mtlTmp);
			// Fix the shine
			$mtlTmp = preg_replace('/^Ns \d+/im', 'Ns 0', $mtlTmp);
			$mtlTmp = preg_replace('/^Ks.+/im', 'Ks 0.0627451 0.0627451 0.0627451', $mtlTmp);
			
			foreach($content->files as $file => $fileB64) {
				$extension = strtolower(substr(strrchr($file, '.'), 1));
				
				if($extension != 'obj' && $extension != 'mtl')
					$extension = 'textures';
				
				if($extension == 'mtl')
					continue;
				
				$cdnHash = CdnHelper::SaveContentB64($fileB64->content, ($extension == 'png' ? 'image/png' : 'text/plain'));
				$mtlTmp = str_replace($file, $cdnHash, $mtlTmp);
				
				if(array_key_exists($extension, $result)) {
					if(gettype($result[$extension]) == 'array')
						array_push($result[$extension], $cdnHash);
					else
						$result[$extension] = $cdnHash;
				} else {
					$result[$extension] = $cdnHash;
				}
			}
			
			$result['mtl'] = CdnHelper::SaveContent($mtlTmp, 'text/plain');
			
			$this->tracker->targetObj->set3DHash(CdnHelper::SaveContent(json_encode($result), 'text/plain'));
		} else {
			$this->tracker->targetObj->set2DHash(CdnHelper::SaveContentB64($result, 'image/png'));
		}
		
		$this->tracker->delete();
    }
}
