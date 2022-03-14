<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\RegisterController;
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

Route::get('/banners/data', 'BannerController@getBanners');

Route::get('/games/metadata', 'GamesController@isAvailable');

Route::get('/fetch/categories', 'Controller@fetchCategories');

Route::get('/fetch/categories/post', 'Controller@fetchCategoriesFP');

Route::get('/fetch/category/{id}', 'Controller@fetchCategory');

Route::get('/fetch/posts/{id}', 'Controller@fetchPosts');

Route::get('/fetch/post/{id}', 'Controller@fetchPost');

Route::post('/fetch/user', 'Controller@fetchUser');

Route::post('/maintenance/bypass', 'MaintenanceController@bypass');

Route::post('/account/register', 'Auth\RegisterController@create');

Route::post('/account/login', 'Controller@login');

Route::post('/api/create/forum', 'HomeController@createPost');

Route::post('/api/create/reply/{id}', 'HomeController@createReply');

Route::fallback(function(){
	return response('{"errors":[{"code":404,"message":"NotFound"}]}', 404)
		->header('Cache-Control', 'private')
		->header('Content-Type', 'application/json; charset=utf-8');
});