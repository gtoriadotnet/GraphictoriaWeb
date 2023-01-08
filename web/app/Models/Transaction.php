<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
	
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
	
	protected static function createPurchase($transaction)
	{
		return self::create(array_merge($transaction, [
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
			'delta' => $asset->priceInTokens,
			'seller_id' => $asset->creatorId
		];
		
		// XlXi: Assets have a 30% tax.
		return [
			self::createPurchase($transaction),
			self::createSale(array_merge($transaction, ['delta' => $transaction['delta'] * (1-.3)]))
		];
	}
}
