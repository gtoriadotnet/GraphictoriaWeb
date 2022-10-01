<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicWebConfiguration extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'masked' => 'boolean'
    ];
	
	public function getTruncatedValue($value = null)
	{
		if($value == null)
			$value = $this->value;
		
		if(strlen($value) < 50)
			return $value;
		
		return (substr($value, 0, 50) . '...');
	}
	
	public function getJumbledValue()
	{
		$letters = 'abcdefghijklmnopqrstuvwxyz0123456789 `~!@#$%^&*()-_=+[{]}\\|;:\'",<.>/?';
		$result = '';
		
		for($i=0; strlen($this->value) > $i; $i++)
			$result .= $letters[rand(0, strlen($letters)-1)];
		
		return $this->getTruncatedValue($result);
	}
	
	public function getCreated()
	{
		$date = $this['created_at'];
		if(Carbon::now()->greaterThan($date->copy()->addDays(2)))
			$date = $date->isoFormat('lll');
		else
			$date = $date->calendar();
		
		return $date;
	}
	
	public function getUpdated()
	{
		$date = $this['updated_at'];
		if(Carbon::now()->greaterThan($date->copy()->addDays(2)))
			$date = $date->isoFormat('lll');
		else
			$date = $date->calendar();
		
		return $date;
	}
}
