<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller; // remove this and clean up the code lol
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function(){
	return 'API OK';
});

// Maintenance
Route::post('/v1/maintenance/bypass', 'MaintenanceController@bypass');

// User
Route::post('/v1/user/register', 'AuthController@Register');
Route::post('/v1/user/login', 'AuthController@Login');
Route::post('/v1/user/logout', 'AuthController@Logout');
Route::get('/v1/user/settings', 'UserController@GetSettings');

Route::get('/fetch/user/{id}', 'Controller@fetchUser');

Route::get('/banners/data', 'BannerController@getBanners');

Route::get('/games/metadata', 'GamesController@isAvailable');

Route::get('/fetch/feed', 'Controller@fetchFeed');

Route::post('/api/create/feed', 'HomeController@createFeed');

Route::get('/fetch/categories', 'Controller@fetchCategories');

Route::post('/fetch/categories/post', 'Controller@fetchCategoriesFP');

Route::get('/fetch/categories/catalog', 'Controller@fetchCategoriesCatalog');

Route::get('/fetch/category/catalog/{id}', 'Controller@fetchCategoryCatalog');

Route::get('/fetch/category/{id}', 'Controller@fetchCategory');

Route::get('/fetch/posts/{id}', 'Controller@fetchPosts');

Route::get('/fetch/post/{id}', 'Controller@fetchPost');

Route::get('/fetch/item/{id}', 'Controller@fetchItem');

Route::post('/api/add/user/{id}', 'HomeController@addFriend');

Route::post('/api/create/forum', 'HomeController@createPost');

Route::post('/api/create/reply/{id}', 'HomeController@createReply');

Route::post('/api/catalog/buy/{id}', 'CatalogController@buy');

Route::post('/api/catalog/sell/{id}', 'CatalogController@sell');

Route::post('/api/change/user/about', 'SettingsController@settingsAbout');

Route::post('/api/change/user/password', 'SettingsController@settingsPassword');

Route::post('/api/change/user/email', 'SettingsController@settingsEmail');

Route::fallback(function(){
	return response('{"errors":[{"code":404,"message":"NotFound"}]}', 404)
		->header('Cache-Control', 'private')
		->header('Content-Type', 'application/json; charset=utf-8');
});