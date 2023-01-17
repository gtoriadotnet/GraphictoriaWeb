<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvatarAsset extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'owner_id',
		'asset_id'
	];
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
