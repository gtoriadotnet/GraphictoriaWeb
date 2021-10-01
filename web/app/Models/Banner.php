<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
	protected $casts = [
	  'dismissable' => 'boolean',
	];
	
    use HasFactory;
}
