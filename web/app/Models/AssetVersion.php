<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetVersion extends Model
{
    use HasFactory;
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'parentAsset');
    }
}
