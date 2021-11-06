<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\GamesController;

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

Route::get('/', function () {
    return 'API OK';
});

Route::get('/banners/data', [BannerController::class, 'getBanners']);

Route::get('/games/metadata', [GamesController::class, 'isAvailable']);

Route::fallback(function () {
	return response('{"errors":[{"code":404,"message":"NotFound"}]}', 404)
		->header('Cache-Control', 'private')
		->header('Content-Type', 'application/json; charset=utf-8');
});