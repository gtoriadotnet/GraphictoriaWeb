<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AppDeploymentFilenameRule implements Rule
{
	protected $appType;
	
    /**
     * Create a new rule instance.
     *
     * @param  string  $appType
     * @return void
     */
    public function __construct(string $appType)
    {
        $this->appType = $appType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
		if(!$value)
			return false;
		
		$files = [
			'content-fonts.zip',
			'content-music.zip',
			'content-particles.zip',
			'content-sky.zip',
			'content-sounds.zip',
			'content-terrain.zip',
			'content-textures.zip',
			'content-textures2.zip',
			'content-textures3.zip',
			'shaders.zip',
			'redist.zip',
			'libraries.zip'
		];
		
		if($this->appType == 'client')
		{
			array_push($files, ...[
				'playerpdb.zip',
				'virtubrick.zip',
				'virtubrickplayerlauncher.exe'
			]);
		}
		elseif($this->appType == 'studio')
		{
			array_push($files, ...[
				'builtinplugins.zip',
				'imageformats.zip',
				'content-scripts.zip',
				'studiopdb.zip',
				'virtubrickstudio.zip',
				'virtubrickstudiolauncherbeta.exe'
			]);
		}
		
		$neededFiles = count($files);
		if(count($value) != $neededFiles)
			return false;
		
		foreach($value as $file)
		{
			if(!in_array(strtolower($file->getClientOriginalName()), $files))
				return false;
		}
		
		return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Missing files.';
    }
}
