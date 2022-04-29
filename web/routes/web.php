<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// web
Route::view('/', 'home');

Route::any('/login', 'Auth\\AuthController@login');
Route::any('/register', 'Auth\\AuthController@register');
Route::any('/maintenance', 'MaintenanceController@showPage');

// misc
Route::view('/javascript', 'javascript');
Route::view('/testing/blank', 'blank');

// client
Route::get('/asset', 'ContentController@fetchAsset');

// fallback
Route::fallback(function(){
	return response()->view('errors.404', [], 404);
});