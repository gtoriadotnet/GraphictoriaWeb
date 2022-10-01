<?php

Route::get('/', 'ApiController@index')->name('index');

Route::get('/ping', function() {
	return response('');
})->middleware('lastseen');

Route::middleware('auth')->group(function () {
	Route::group(['as' => 'feed.', 'prefix' => 'feed'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::get('/list-json', 'FeedController@listJson')->name('list');
			Route::post('/share', 'FeedController@share')->name('share')->middleware('throttle:3,2');
		});
	});
});

Route::group(['as' => 'comments.', 'prefix' => 'comments'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/list-json', 'CommentsController@listJson')->name('list');
		Route::post('/share', 'CommentsController@share')->name('share')->middleware(['auth', 'throttle:3,2']);
	});
});

Route::group(['as' => 'shop.', 'prefix' => 'shop'], function() {
	Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
		Route::get('/list-json', 'ShopController@listJson')->name('list');
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