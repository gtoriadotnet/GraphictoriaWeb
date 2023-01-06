<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobloxAsset extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'localAssetId',
		'robloxAssetId'
	];
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'localAssetId');
    }
}
