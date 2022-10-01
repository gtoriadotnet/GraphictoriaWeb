<?php

use App\Models\Asset;
use App\Models\User;

Route::group(['as' => 'user.', 'prefix' => 'users'], function() {
	Route::get('/{user}/profile', 'ProfileController@index')->name('profile');
});

Route::group(['as' => 'home.'], function() {
	Route::get('/', 'HomeController@landing')->name('landing')->middleware('guest');
	Route::get('/my/dashboard', 'HomeController@dashboard')->name('dashboard')->middleware('auth');
});

Route::group(['as' => 'shop.', 'prefix' => 'shop'], function() {
	Route::get('/', 'ShopController@index')->name('index');
	Route::get('/{asset}/{assetName:slug?}', 'ShopController@showAsset')->name('asset');
});

Route::group(['as' => 'games.', 'prefix' => 'games'], function() {
	Route::get('/', 'GamesController@index')->name('index');
	//Route::get('/{asset}/{assetName:slug?}', 'GamesController@showGame')->name('game');
});

Route::middleware('auth')->group(function () {
	Route::group(['as' => 'user.', 'prefix' => 'my'], function() {
		Route::get('/settings', 'SettingsController@index')->name('index');
	});
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function() {
	Route::middleware('roleset:moderator')->group(function () {
		Route::get('/', 'AdminController@dashboard')->name('dashboard');
	});
	
	Route::middleware('roleset:administrator')->group(function () {
		Route::get('/arbiter-diag/{arbiterType?}', 'AdminController@arbiterDiag')->name('diag');
	});
	
	Route::middleware('roleset:owner')->group(function () {
		Route::get('/arbiter-management/{arbiterType?}/{jobId?}', 'AdminController@arbiterManagement')->name('arbitermanagement');
		
		Route::get('/configuration', 'AdminController@configuration')->name('configuration');
	});
});

Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function() {
	Route::group(['as' => 'protection.', 'prefix' => 'request-blocked'], function() {
		Route::get('/', 'DoubleSessionBlockController@index')->name('index');
		Route::post('/', 'DoubleSessionBlockController@store')->name('bypass');
	});
	
	Route::get('/moderation-notice', 'UserModerationController@index')->middleware(['auth', 'banned'])->name('moderation.notice');

	Route::middleware('guest')->group(function () {
		Route::group(['as' => 'register.', 'prefix' => 'register'], function() {
			Route::get('/', 'RegisteredUserController@index')->name('index');
			Route::post('/', 'RegisteredUserController@store')->name('submit');
		});
		
		Route::group(['as' => 'login.', 'prefix' => 'login'], function() {
			Route::get('/', 'AuthenticatedSessionController@index')->name('index');
			Route::post('/', 'AuthenticatedSessionController@store')->name('submit');
		});
		
		Route::group(['as' => 'password.', 'prefix' => 'forgot-password'], function() {
			Route::get('/', 'PasswordResetLinkController@index')->name('forgot');
			Route::post('/', 'PasswordResetLinkController@store')->name('forgot-submit');
		});
		
		Route::group(['as' => 'password.', 'prefix' => 'reset-password'], function() {
			Route::get('/{token}', 'NewPasswordController@index')->name('reset');
			Route::post('/{token}', 'NewPasswordController@store')->name('reset-submit');
		});
	});
	
	Route::middleware('auth')->group(function () {
		Route::group(['as' => 'verify-email.', 'prefix' => 'verify-email'], function() {
			Route::get('/', 'EmailVerificationPromptController@__invoke')->name('index');
			Route::post('/', 'EmailVerificationNotificationController@store')->middleware('throttle:6,1')->name('submit');
			Route::get('/{hash}', 'VerifyEmailController@__invoke')->middleware(['signed', 'throttle:6,1'])->name('verify');
		});
		

		Route::group(['as' => 'password.', 'prefix' => 'confirm-password'], function() {
			Route::get('/', 'ConfirmablePasswordController@index')->name('confirm');
			Route::post('/', 'ConfirmablePasswordController@store')->name('confirm-submit');
		});
		
		Route::get('/logout', 'AuthenticatedSessionController@destroy')->name('logout');
	});
});

Route::group(['as' => 'client.'], function() {
	Route::get('/asset', 'ClientController@asset')->name('asset');
});