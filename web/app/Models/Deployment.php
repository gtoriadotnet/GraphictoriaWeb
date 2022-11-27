<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
		'updated_at' => 'datetime'
    ];
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'version',
		'app',
		'type'
	];
	
	static function newVersionHash($config)
	{
		// XlXi: We want a GUID here, not a UUID. This is why we're not using Str::uuid().
		$hash = preg_replace('/[^a-z0-9]+/i', '', com_create_guid());
		$hash = substr($hash, 0, 16);
		$hash = strtolower($hash);
		
		return self::create([
			'version' => sprintf('version-%s', $hash),
			'app' => strtolower($config['app']),
			'type' => strtolower($config['type'])
		]);
	}
	
	function isValid()
	{
		$isValid = $this->created_at > Carbon::now()->subMinute(3);
		if(!$isValid)
		{
			$this->delete();
		}
		
		return $isValid;
	}
}
