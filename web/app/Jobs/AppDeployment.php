<?php

namespace App\Jobs;

use COM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

use App\Models\Deployment;
use App\Models\DynamicWebConfiguration;

class AppDeployment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	protected $deployment;
	
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->deployment->step = 2; // Unpacking files.
		$this->deployment->save();
		
		$workingDirectory = storage_path(sprintf('app/setuptmp/%s', $this->deployment->version));
		Storage::makeDirectory($workingDirectory);
		
		$appArchive = '';
		$appName = '';
		$bootstrapperName = '';
		$bootstrapperVersionName = '';
		switch($this->deployment->app)
		{
			case 'client':
				$appArchive = 'GraphictoriaApp.zip';
				$appName = 'GraphictoriaPlayer.exe';
				$bootstrapperName = 'GraphictoriaPlayerLauncher.exe';
				$bootstrapperVersionName = 'BootstrapperVersion.txt';
				break;
			case 'studio':
				$appArchive = 'GraphictoriaStudio.zip';
				$appName = 'GraphictoriaStudio.exe';
				$bootstrapperName = 'GraphictoriaStudioLauncherBeta.exe';
				$bootstrapperVersionName = 'BootstrapperQTStudioVersion.txt';
				break;
		}
		$bootstrapperLocation = sprintf('%s/../%s-%s', $workingDirectory, $this->deployment->version, $bootstrapperName);
		
		$zip = new ZipArchive();
		$zip->open(sprintf(
			'%s/../%s-%s',
			$workingDirectory,
			$this->deployment->version,
			$appArchive
		));
		$zip->extractTo($workingDirectory);
		$zip->close();
		
		$this->deployment->step = 3; // Updating version security.
		$this->deployment->save();
		
		// XlXi: this will not work on linux.
		$fso = new COM("Scripting.FileSystemObject");
		$appVersion = $fso->GetFileVersion(sprintf('%s/%s', $workingDirectory, $appName));
		$bootstrapperVersion = $fso->GetFileVersion($bootstrapperLocation);
		
		$hashConfig = DynamicWebConfiguration::where('name', sprintf('%sUploadVersion', $this->deployment->app))->first();
		$versionConfig = DynamicWebConfiguration::where('name', sprintf('%sDeployVersion', $this->deployment->app))->first();
		$launcherConfig = DynamicWebConfiguration::where('name', sprintf('%sLauncherDeployVersion', $this->deployment->app))->first();
		
		$hashConfig->value = $this->deployment->version;
		$versionConfig->value = $appVersion;
		$launcherConfig->value = $bootstrapperVersion;
		$hashConfig->save();
		$versionConfig->save();
		$launcherConfig->save();
		
		$this->deployment->step = 4; // Pushing to setup.
		$this->deployment->save();
		
		Storage::copy(sprintf('setuptmp/%s-%s', $this->deployment->version, $bootstrapperName), sprintf('setup/%s', $bootstrapperName));
		Storage::put(sprintf('setup/%s-%s', $this->deployment->version, $bootstrapperVersionName), str_replace('.', ', ', $bootstrapperVersion));
		Storage::put(sprintf('setup/%s-gtManifest.txt', $this->deployment->version), '');
		
		$files = Storage::files('setuptmp');
		foreach($files as $file)
		{
			$fileName = str_replace('setuptmp/', '', $file);
			
			if(str_starts_with($fileName, $this->deployment->version))
				Storage::move($file, sprintf('setup/%s', $fileName));
		}
		
		Storage::deleteDirectory(sprintf('setuptmp/%s', $this->deployment->version));
		
		$this->deployment->step = 5; // Success.
		$this->deployment->save();
    }
	
	public function failed($exception)
	{
		$this->deployment->error = $exception->getMessage();
		$this->deployment->save();
	}
}