<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\HasApiTokens;

use App\Helpers\CdnHelper;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'next_reward' => 'datetime',
        'created_at' => 'datetime',
        'last_seen' => 'datetime',
    ];
	
	/**
	 * Send a password reset notification to the user.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$url = route('auth.password.reset-submit', ['token' => $token]);
		
		$this->notify(new ResetPasswordNotification($url, $this));
	}
	
	public function getFriendRequests()
	{
		return Friend::where('receiver_id', Auth::user()->id)
						->where('accepted', false);
	}
	
	public function getProfileUrl()
	{
		return route('user.profile', ['user' => $this->id]);
	}
	
	public function getLastSeen()
	{
		if($this->last_seen >= Carbon::now()->subMinutes(2))
			return 'Now';
		
		if(Carbon::now() >= $this->last_seen->copy()->addDays(2))
			return $this->last_seen->isoFormat('ll');
		else
			return $this->last_seen->calendar();
	}
	
	public function getJoinDate()
	{
		return $this->created_at->isoFormat('ll');
	}
	
	public function getCensoredEmail()
	{
		$email = $this->email;
		
		$bits = explode('@', $email);
		$name = implode('@', array_slice($bits, 0, count($bits) - 1));
		$length = floor(strlen($name) / 2);
		
		return substr($name, 0, $length) . str_repeat('*', $length) . "@" . end($bits);
	}
	
	// XlXi: TODO: Replace this with detailed presence
	//		       like what game the user is in or
	//			   what place they're editing.
	public function isOnline()
	{
		return ($this->last_seen >= Carbon::now()->subMinutes(2));
	}
	
	public function getRolesets()
	{
		return UserRoleset::where('User_id', $this->id);
	}
	
	public function saveIp($ip)
	{
		$ipExists = UserIp::where('userId', $this->id)
							->where('ipAddress', $ip)
							->exists();
		if(!$ipExists)
		{
			return UserIp::create([
				'userId' => $this->id,
				'ipAddress' => $ip
			]);
		}
		
		return false;
	}
	
	public function hasActivePunishment()
	{
		return Punishment::activeFor($this->id)->exists();
	}
	
	public function getPunishment()
	{
		return Punishment::activeFor($this->id)->first();
	}
	
	public function punishments()
	{
		return $this->hasMany(Punishment::class, 'user_id')->orderByDesc('id');
	}
	
	public function hasAsset($assetId)
	{
		return UserAsset::where('asset_id', $assetId)
						->where('owner_id', $this->id)
						->exists();
	}
	
	public function removeFunds($delta)
	{
		$this->tokens -= $delta;
		$this->save();
	}
	
	public function addFunds($delta)
	{
		$this->tokens += $delta;
		$this->save();
	}
	
	public function getImageUrl()
	{
		if(!$this->thumbnail2DHash)
		{
			$thumbnail = Http::get(route('thumbnails.v1.user', ['id' => $this->id, 'position' => 'full', 'type' => '2d']));
			if($thumbnail->json('status') == 'loading')
				return '/images/busy/user.png';
			
			return $thumbnail->json('data');
		}
		
		return route('content', $this->thumbnail2DHash);
	}
	
	public function getHeadshotImageUrl()
	{
		if(!$this->thumbnailBustHash)
		{
			$thumbnail = Http::get(route('thumbnails.v1.user', ['id' => $this->id, 'position' => 'bust', 'type' => '2d']));
			if($thumbnail->json('status') == 'loading')
				return '/images/busy/user.png';
			
			return $thumbnail->json('data');
		}
		
		return route('content', $this->thumbnailBustHash);
	}
	
	public function set2DHash($hash)
	{
		$this->thumbnail2DHash = $hash;
		$this->timestamps = false;
		$this->save();
	}
	
	public function setBustHash($hash)
	{
		$this->thumbnailBustHash = $hash;
		$this->timestamps = false;
		$this->save();
	}
	
	public function set3DHash($hash)
	{
		$this->thumbnail3DHash = $hash;
		$this->timestamps = false;
		$this->save();
	}
	
	public function redraw()
	{
		$oldHashes = [
			$this->thumbnailBustHash,
			$this->thumbnail2DHash,
			$this->thumbnail3DHash
		];
		
		$this->thumbnailBustHash = null;
		$this->thumbnail2DHash = null;
		$this->thumbnail3DHash = null;
		$this->timestamps = false;
		$this->save();
		
		foreach($oldHashes as $hash)
		{
			$userThumbExists = User::where('thumbnailBustHash', $hash)->orWhere('thumbnail2DHash', $hash)->orWhere('thumbnail3DHash', $hash)->exists();
			$assetThumbExists = Asset::where('thumbnail2DHash', $hash)->orWhere('thumbnail3DHash', $hash)->exists();
			if(!$userThumbExists && !$assetThumbExists)
				CdnHelper::Delete($hash);
		}
	}
	
	public function isWearing($assetId)
	{
		return AvatarAsset::where('owner_id', $this->id)
							->where('asset_id', $assetId)
							->exists();
	}
	
	public function getWearing()
	{
		return AvatarAsset::where('owner_id', $this->id)
							->whereRelation('asset', 'moderated', 0);
	}
	
	public function wearAsset($assetId)
	{
		$asset = Asset::where('id', $assetId)->first();
		if($asset->assetType->id == 32)
		{
			foreach(explode(';', $asset->getPackageAssetIds()) as $asset)
			{
				if($this->isWearing($asset)) continue;
				
				$this->wearAsset($asset);
			}
		}
		else
		{
			AvatarAsset::Create([
				'owner_id' => $this->id,
				'asset_id' => $assetId
			]);
		}
	}
	
	public function getBodyColors()
	{
		$colors = AvatarColor::user($this->id);
		if($colors->exists())
			return $colors->first();
		
		return AvatarColor::newForUser($this->id);
	}
	
	public function changeName($newName)
	{
		if($newName == $this->username) return false;
		
		Username::create([
			'username' => $newName,
			'user_id' => $this->id
		]);
		
		$this->username = $newName;
		$this->save();
	}
	
	public function userToJson()
	{
		return [
			'type' => 'User',
			'name' => $this->username,
			'thumbnail' => $this->getHeadshotImageUrl(),
			'url' => $this->getProfileUrl()
		];
	}
	
	public function _hasRolesetInternal($roleName)
	{
		$roleset = Roleset::where('Name', $roleName)->first();
		if(
			UserRoleset::where('Roleset_id', $roleset->id)
						->where('User_id', $this->id)
						->exists()
		)
			return true;
		
		return false;
	}
	
	public function hasRoleset($roleName)
	{
		if(!Auth::check())
			return false;
		
		$roleName = strtolower($roleName);
		
		// Special cases for Owner and Administrator rolesets
		if($roleName == 'moderator') {
			return (
				$this->_hasRolesetInternal('Owner') ||
				$this->_hasRolesetInternal('Administrator') ||
				$this->_hasRolesetInternal('Moderator')
			);
		} elseif($roleName == 'administrator') {
			return (
				$this->_hasRolesetInternal('Owner') ||
				$this->_hasRolesetInternal('Administrator')
			);
		}
		
		return $this->_hasRolesetInternal($roleName);
	}
}
