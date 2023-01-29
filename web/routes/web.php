<?php

use App\Models\Asset;
use App\Models\User;

if (config('app.testenv'))
{
	Route::group(['as' => 'testing.', 'prefix' => 'testing'], function() {
		Route::get('/info', 'TestSiteController@info')->name('info');
	});
}

Route::group(['as' => 'maintenance.', 'prefix' => 'maintenance'], function() {
	Route::get('/', 'MaintenanceController@index')->name('index');
});

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
	Route::get('/{asset}/{assetName:slug?}', 'GamesController@showGame')->name('asset');
});

Route::middleware('auth')->group(function () {
	Route::group(['as' => 'user.', 'prefix' => 'my'], function() {
		Route::get('/settings', 'SettingsController@index')->name('settings');
		Route::get('/avatar', 'AvatarController@index')->name('avatarEditor');
		Route::get('/transactions', 'MoneyController@transactions')->name('transactions');
	});
	
	Route::group(['as' => 'punishment.', 'prefix' => 'membership'], function() {
		 Route::get('/not-approved', 'ModerationController@notice')->name('notice');
		 Route::post('/not-approved', 'ModerationController@reactivate')->name('reactivate');
	});
});

Route::group(['as' => 'admin.'], function() {
	Route::get('/js/adm/{jsFile}', 'AdminController@getJs')->middleware('roleset:moderator');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function() {
	Route::middleware('roleset:moderator')->group(function () {
		Route::get('/', 'AdminController@dashboard')->name('dashboard');
		
		Route::group(['prefix' => 'users'], function() {
			Route::get('/useradmin', 'AdminController@userAdmin')->name('useradmin');
			Route::get('/manualmoderateuser', 'AdminController@manualModerateUser')->name('manualmoderateuser');
			Route::post('/manualmoderateuser', 'AdminController@manualModerateUserSubmit')->name('manualmoderateusersubmit');
			Route::get('/find', 'AdminController@userSearch')->name('usersearch');
			Route::get('/userlookuptool', 'AdminController@userLookup')->name('userlookup');
			Route::post('/userlookuptool', 'AdminController@userLookupQuery')->name('userlookupquery');
		});
	});
	
	Route::middleware('roleset:administrator')->group(function () {
		Route::group(['prefix' => 'catalog'], function() {
			Route::get('/autoassetupload', 'AdminController@autoUpload')->name('autoupload');
			Route::get('/manualassetupload', 'AdminController@assetUpload')->name('assetupload');
			Route::get('/uploadedassets', 'AdminController@getAdminUploads')->name('adminuploads');
		});
		
		Route::get('/metrics', 'AdminController@metricsVisualization')->name('metricsvisualization');
		
		Route::get('/arbiter-diag/{arbiterType?}', 'AdminController@arbiterDiag')->name('diag');
	});
	
	Route::middleware('roleset:owner')->group(function () {
		Route::get('/arbiter-management/{arbiterType?}/{jobId?}', 'AdminController@arbiterManagement')->name('arbitermanagement');
		
		Route::get('/configuration', 'AdminController@configuration')->name('configuration');
		
		Route::get('/deploy', 'AdminController@deployer')->name('deployer');
	});
});

Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function() {
	//Route::group(['as' => 'protection.', 'prefix' => 'request-blocked'], function() {
	//	Route::get('/', 'DoubleSessionBlockController@index')->name('index');
	//	Route::post('/', 'DoubleSessionBlockController@store')->name('bypass');
	//});
	
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

Route::withoutMiddleware(['csrf'])->group(function () {
	Route::group(['as' => 'client.'], function() {
		Route::get('/asset', 'ClientController@asset')->name('asset');
		Route::group(['prefix' => 'asset'], function() {
			Route::get('/CharacterFetch.ashx', 'ClientAvatarController@characterFetch')->name('characterFetch');
			Route::get('/BodyColors.ashx', 'ClientAvatarController@bodyColors')->name('bodyColors');
		});
		
		Route::group(['as' => 'game.', 'prefix' => 'game'], function() {
			Route::post('/PlaceLauncher', 'ClientGameController@placeLauncher')->name('placelauncher');
		});
	});
});

Route::fallback(function() {
	return view('errors.404');
});