<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;

class MoneyController extends Controller
{
    public function userSummary(Request $request)
	{
		$result = [
			'columns' => [],
			'total' => 0
		];
		
		$dataPoints = [
			[
				'Name' => 'Item Purchases',
				'Points' => ['Purchases']
			],
			[
				'Name' => 'Sale of Goods',
				'Points' => ['Sales', 'Commissions']
			],
			[
				'Name' => 'Group Payouts',
				'Points' => ['Group Payouts']
			]
		];
		
		foreach($dataPoints as $dataPoint)
		{
			$newColumn = ['name' => $dataPoint['Name'], 'total' => 0];
			
			foreach($dataPoint['Points'] as $transactionType)
			{
				$column = $transactionType == 'Sales' ? 'seller_id' : 'user_id';
				
				$newColumn['total'] += Transaction::where($column, Auth::user()->id)
										->where('transaction_type_id', TransactionType::IDFromType($transactionType))
										->where(function($query) use($request) {
											if(!$request->has('filter'))
												return $query;
											
											$now = Carbon::now();
											switch($request->get('filter'))
											{
												case 'pastday':
													return $query->where('created_at', '>', $now->subDay());
												case 'pastweek':
													return $query->where('created_at', '>', $now->subWeek());
												case 'pastmonth':
													return $query->where('created_at', '>', $now->subMonth());
												case 'pastyear':
													return $query->where('created_at', '>', $now->subYear());
												default:
													return $query;
											}
										})
										->sum('delta');
			}
			
			array_push($result['columns'], $newColumn);
			$result['total'] += $newColumn['total'];
		}
		
		return response($result);
	}
	
	public function userTransactions(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'filter' => ['required', 'in:purchases,sales,commissions,grouppayouts']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		$resultData = [];
		$transactionType = 0;
		switch($valid['filter'])
		{
			case 'purchases':
				$transactionType = TransactionType::where('name', 'Purchases')->first();
				break;
			case 'sales':
				$transactionType = TransactionType::where('name', 'Sales')->first();
				break;
			case 'commissions':
				$transactionType = TransactionType::where('name', 'Commissions')->first();
				break;
			case 'grouppayouts':
				$transactionType = TransactionType::where('name', 'Group Payouts')->first();
				break;
		}
		
		$transactions = Transaction::where(function($query) use($valid) {
										if($valid['filter'] == 'sales')
											return $query->where('seller_id', Auth::user()->id);
										return $query->where('user_id', Auth::user()->id);
									})
									->where('transaction_type_id', $transactionType->id)
									->with('asset')
									->orderByDesc('id')
									->cursorPaginate(30);
		$prevCursor = $transactions->previousCursor();
		$nextCursor = $transactions->nextCursor();
		
		foreach($transactions as $transaction)
		{
			$user = null;
			if($valid['filter'] != 'sales')
				$user = $transaction->seller;
			else
				$user = $transaction->user;
			
			$asset = null;
			if($transactionType->format != '')
				$asset = [
					'url' => $transaction->asset->getShopUrl(),
					'name' => $transaction->asset->name
				];
			
			array_push($resultData, [
				'date' => $transaction->created_at->isoFormat('lll'),
				'member' => $user->userToJson(),
				'description' => $transactionType->format,
				'amount' => $transaction->delta,
				'item' => $asset
			]);
		}
		
		return response([
			'data' => $resultData,
			'prev_cursor' => ($prevCursor ? $prevCursor->encode() : null),
			'next_cursor' => ($nextCursor ? $nextCursor->encode() : null)
		]);
	}
}
