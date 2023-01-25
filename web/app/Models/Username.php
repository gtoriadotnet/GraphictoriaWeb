<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Username extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'user_id',
		'scrubbed_by'
    ];
	
	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
