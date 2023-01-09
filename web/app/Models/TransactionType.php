<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;
	
	public static function IDFromType($type)
	{
		return self::where('name', $type)->first()->id;
	}
}
