<?php

namespace App\Jobs;

use claviska\SimpleImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use App\Grid\SoapService;
use App\Helpers\CdnHelper;
use App\Helpers\GridHelper;
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
     * Asset type string based on asset's type id
     *
     * @var string
     */
	public $type;
	
	/**
     * Asset ID to render
     *
     * @var int
     */
	public $assetId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RenderTracker $tracker, bool $is3D, string $type, int $assetId)
    {
        $this->tracker = $tracker;
        $this->is3D = $is3D;
        $this->type = $type;
        $this->assetId = $assetId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		// TODO: XlXi: User avatar/closeup render support.
		$arguments = [
			route('client.asset', ['id' => $this->assetId]),
			($this->is3D ? 'OBJ' : 'PNG'),
			420*4, // Width    // XlXi: These get scaled down by 4.
			420*4, // Height   // XlXi: These get scaled down by 4.
			url('/') . '/'
		];
		
		switch($this->type) {
			case 'Bust':
				$this->type = 'Closeup';
				array_push($arguments, false); // Quadratic
				array_push($arguments, 30); // Base Hat Zoom
				array_push($arguments, 100); // Max Hat Zoom
				array_push($arguments, 0); // Camera Offset X
				array_push($arguments, 0); // Camera Offset Y
			case 'Avatar':
				$arguments[0] = route('client.characterFetch', ['userId' => $this->assetId]);
				break;
			case 'Face':
				$this->type = 'Decal';
				break;
			case 'Torso':
			case 'Right Arm':
			case 'Left Arm':
			case 'Left Leg':
			case 'Right Leg':
				$this->type = 'BodyPart';
			case 'Head':
			case 'Shirt':
			case 'Pants':
				// TODO: XlXi: Move this to config, as it could be different from prod in a testing environment. Also move this to it's own asset (not loading from roblox).
				array_push($arguments, 'https://www.roblox.com/asset/?id=1785197'); // Rig
				break;
			case 'Package':
				// TODO: XlXi: Move these to config, as it could be different from prod in a testing environment. Also move these to their own assets (not loading from roblox).
				$arguments[0] = $this->tracker->targetObj->getPackageAssetUrls();
				array_push($arguments, 'https://www.roblox.com/asset/?id=1785197'); // Rig
				array_push($arguments, 'https://www.roblox.com/asset/?id=27113661;https://www.roblox.com/asset/?id=25251154'); // Custom Texture URLs (shirt and pands)
				break;
			case 'Place':
				$arguments[2] = 768*4; // XlXi: These get scaled down by 4.
				$arguments[3] = 432*4; // XlXi: These get scaled down by 4.
				array_push($arguments, '0'); // TODO: XlXi: Universe IDs
				break;
		}
		
		$service = new SoapService('Thumbnail');
		$result = $service->OpenJob(GridHelper::JobTemplate(
			Str::uuid()->toString(), // Job ID
			120, // Expiration
			0, // Category ID
			0, // Cores
			sprintf('Render %s %d', $this->tracker->type, $this->tracker->target), // Script Name
			$this->type, // Script
			$arguments // Arguments
		));
		
		if(is_soap_fault($result))
		{
			$this->tracker->delete();
			$this->fail(sprintf('SOAP Fault: (faultcode: %s, faultstring: %s)', $result->faultcode, $result->faultstring));
		}
		
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
			$image = new SimpleImage();
			$image = $image
				->fromString(base64_decode($result))
				->resize($arguments[2]/4, $arguments[3]/4)
				->toString();
			
			if($this->type == 'Closeup')
				$this->tracker->targetObj->setBustHash(CdnHelper::SaveContentB64(base64_encode($image), 'image/png'));
			else
				$this->tracker->targetObj->set2DHash(CdnHelper::SaveContentB64(base64_encode($image), 'image/png'));
		}
		
		$this->tracker->delete();
    }
}
