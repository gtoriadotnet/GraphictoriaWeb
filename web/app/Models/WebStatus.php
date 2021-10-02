<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebStatus extends Model
{
	protected $casts = [
	  'operational' => 'boolean',
	];
	
    use HasFactory;
}
