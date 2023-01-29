<?php

Route::get('/', 'ApiController@index')->name('index');

Route::get('/ping', function() {
	return response('');
})->middleware('lastseen');

Route::group(['as' => 'maintenance.', 'prefix' => 'maintenance'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::post('/bypass', 'MaintenanceController@bypass')->name('bypass')->middleware('throttle:20,30,maintenance');
	});
});

Route::middleware('auth')->group(function () {
	Route::group(['as' => 'client.', 'prefix' => 'auth'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::get('/generate-token', 'ClientController@generateAuthTicket')->name('generatetoken')->middleware('throttle:40,10,clientticket');
		});
	});
	
	Route::group(['as' => 'feed.', 'prefix' => 'feed'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::get('/list-json', 'FeedController@listJson')->name('list');
			Route::post('/share', 'FeedController@share')->name('share')->middleware('throttle:3,2,comments');
		});
	});
	
	Route::group(['as' => 'avatar.', 'prefix' => 'avatar'], function() {
		Route::middleware('throttle:100,10,renders')->group(function () {
			Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
				Route::get('/list', 'AvatarController@listAssets')->name('list');
				Route::get('/wearing', 'AvatarController@listWearing')->name('listWearing');
				Route::get('/body-color', 'AvatarController@getBodyColors')->name('getBodyColors');
				Route::post('/wear', 'AvatarController@wearAsset')->name('wear');
				Route::post('/unwear', 'AvatarController@removeAsset')->name('remove');
				Route::post('/redraw', 'AvatarController@redrawUser')->name('redraw');
				Route::post('/set-body-color', 'AvatarController@setBodyColor')->name('setBodyColor');
			});
		});
	});
	
	Route::group(['as' => 'admin.', 'prefix' => 'admin'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::middleware('roleset:owner')->group(function () {
				Route::get('/deploy', 'AdminController@deploy')->name('deploy');
				Route::post('/deploy/{version}', 'AdminController@deployVersion')->name('deployVersion');
			});
			
			Route::middleware('roleset:administrator')->group(function () {
				Route::post('/manual-asset-upload', 'AdminController@manualAssetUpload')->name('manualAssetUpload')->middleware('throttle:6,2,adminassetupload');
			});
			
			// RCC Only
			Route::get('/upload-rbx-asset', 'AdminController@uploadRobloxAsset')->withoutMiddleware('auth')->name('uploadrbxasset');
			Route::post('/upload-asset', 'AdminController@uploadAsset')->withoutMiddleware('auth')->name('uploadAsset');
		});
	});
});

Route::group(['as' => 'comments.', 'prefix' => 'comments'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/list-json', 'CommentsController@listJson')->name('list');
		Route::post('/share', 'CommentsController@share')->name('share')->middleware(['auth', 'throttle:3,2,comments']);
	});
});

Route::group(['as' => 'shop.', 'prefix' => 'shop'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/list-json', 'ShopController@listJson')->name('list');
		Route::post('/purchase/{asset}', 'ShopController@purchase')->name('purchase');
		
		Route::get('/user-summary', 'MoneyController@userSummary')->name('summary');
		Route::get('/user-transactions', 'MoneyController@userTransactions')->name('transactions');
	});
});

Route::group(['as' => 'games.', 'prefix' => 'games'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/list-json', 'GamesController@listJson')->name('list');
	});
});

Route::group(['as' => 'thumbnails.', 'prefix' => 'thumbnails'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/try-asset', 'ThumbnailController@tryAsset')->name('try')->middleware('auth');
		Route::get('/asset', 'ThumbnailController@renderAsset')->name('asset');
		Route::get('/user', 'ThumbnailController@renderUser')->name('user');
	});
});

Route::fallback(function () {
    return response('404 not found.', 404)
				->header('Content-Type', 'text/plain');
});