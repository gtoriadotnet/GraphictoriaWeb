<?php

Route::get('/', 'ApiController@index')->name('index');

Route::middleware('auth')->group(function () {
	Route::group(['as' => 'feed.', 'prefix' => 'feed'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::get('/list-json', 'FeedController@listjson')->name('list');
			Route::post('/share', 'FeedController@share')->name('share')->middleware('throttle:3,2');
		});
	});
});

Route::group(['as' => 'catalog.', 'prefix' => 'catalog'], function() {
		Route::group(['as' => 'v1.', 'prefix' => 'v1'], function() {
			Route::get('/list-json', 'ShopController@listjson')->name('list');
		});
	});

Route::fallback(function () {
    return response('404 not found.', 404)
				->header('Content-Type', 'text/plain');
});