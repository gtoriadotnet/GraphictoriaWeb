<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenderTracker extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'type',
		'target'
	];
	
	public function targetObj()
    {
		if($this->type == 'user2d' || $this->type == 'user3d')
			return $this->belongsTo(User::class, 'target');
		elseif($this->type == 'asset2d' || $this->type == 'asset3d')
			return $this->belongsTo(Asset::class, 'target');
    }
}
