<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universe extends Model
{
    use HasFactory;
	
	public function starterPlace()
    {
        return $this->belongsTo(Asset::class, 'startPlaceId');
    }
}
