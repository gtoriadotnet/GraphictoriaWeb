<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvatarColor extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'owner_id',
		'head',
		'torso',
		'leftArm',
		'rightArm',
		'leftLeg',
		'rightLeg'
	];
	
	public static function GetRandomPrimaryColor()
	{
		$colors = [1, 208, 194, 199, 26, 21, 24, 23, 102, 141, 37, 29];
		return $colors[array_rand($colors)];
	}
	
	public static function GetRandomHeadColor()
	{
		$colors = [1, 208, 194, 226];
		return $colors[array_rand($colors)];
	}
	
	public static function user($userId)
	{
		return self::where('owner_id', $userId);
	}
	
	public static function newForUser($userId)
	{
		$headColor = self::GetRandomHeadColor();
		$torsoColor = self::GetRandomPrimaryColor();
		
		return self::create([
			'owner_id' => $userId,
			'head' => $headColor,
			'torso' => $torsoColor,
			'leftArm' => $headColor,
			'rightArm' => $headColor,
			'leftLeg' => 102,
			'rightLeg' => 102
		]);
	}
}
