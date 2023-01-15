<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
	
	/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
		'updated_at' => 'datetime',
    ];
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'transaction_type_id',
		'asset_id',
		'place_id',
		'user_id',
		'delta',
		'seller_id'
	];
	
	public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
	
	public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
	
	protected static function createPurchase($transaction)
	{
		return self::create(array_merge($transaction, [
			'delta' => -$transaction->delta,
			'transaction_type_id' => TransactionType::where('name', 'Purchases')->first()->id
		]));
	}
	
	protected static function createSale($transaction)
	{
		return self::create(array_merge($transaction, [
			'transaction_type_id' => TransactionType::where('name', 'Sales')->first()->id
		]));
	}
	
	public static function createAssetSale($user, $asset, $placeId = null)
	{
		$transaction = [
			'asset_id' => $asset->id,
			'place_id' => $placeId,
			'user_id' => $user->id,
			'delta' => -$asset->priceInTokens,
			'seller_id' => $asset->creatorId
		];
		
		// XlXi: Assets have a 30% tax.
		return [
			self::createPurchase($transaction),
			self::createSale(array_merge($transaction, ['delta' => $transaction['delta'] * -(1-.3)]))
		];
	}
}
