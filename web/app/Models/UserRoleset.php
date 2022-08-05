<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleset extends Model
{
    use HasFactory;
	
	public function roleset()
    {
        return $this->belongsTo(Roleset::class, 'Roleset_id');
    }
	
	public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
