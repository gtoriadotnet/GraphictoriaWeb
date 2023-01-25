<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PunishmentType extends Model
{
    use HasFactory;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'label',
		'time'
	];
	
	
	public function applyNoneState($punishment)
	{
		$user = User::where('id', $punishment['user_id'])->first();
		$userPunishment = $user->getPunishment();
		if(!$userPunishment) return false;
		
		$userPunishment->active = false;
		$userPunishment->pardoner_id = Auth::user()->id;
		$userPunishment->pardoner_note = $punishment['internal_note'];
		$userPunishment->save();
		return $userPunishment;
	}
	
	public function applyToUser($punishment)
	{
		if($this->name == 'None')
			return $this->applyNoneState($punishment);
		
		return Punishment::create(array_merge($punishment, [
					'punishment_type_id' => $this->id,
					'active' => true,
					'expiration' => $this->time !== null ? Carbon::now()->addDays($this->time) : null
				]));
	}
}
