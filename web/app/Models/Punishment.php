<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expiration' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
	
	public function punishment_type()
    {
        return $this->belongsTo(PunishmentType::class, 'punishment_type_id');
    }
	
	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
	
	public function pardoner()
    {
        return $this->belongsTo(User::class, 'pardoner_id');
    }
	
	public function context()
	{
		return $this->hasMany(PunishmentContext::class, 'punishment_id');
	}
	
	public function expired()
	{
		if($this->user->hasRoleset('Owner'))
			return true;
		
		if(!$this->expiration)
			return false;
		
		return !$this->isDeletion() && Carbon::now()->greaterThan($this->expiration);
	}
	
	public function isDeletion()
	{
		return $this->punishment_type->time === null;
	}
	
	public function reviewed()
	{
		return $this->created_at->isoFormat('lll');
	}
	
	public function expirationStr()
	{
		if(!$this->expiration)
			return 'Never';
		
		return $this->created_at->isoFormat('lll');
	}
	
	public static function activeFor($userId)
	{
		return self::where('user_id', $userId)
					->where('active', true)
					->orderByDesc('id');
	}
}
