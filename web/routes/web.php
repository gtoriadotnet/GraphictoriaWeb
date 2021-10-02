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

Route::get('/', function () {
    return view('main');
});

Route::get('/login', function () {
    return view('main');
});

Route::get('/register', function () {
    return view('main');
});

Route::get('/passwordreset', function () {
    return view('main');
});

Route::get('/legal/about-us', function () {
    return view('main');
});

Route::get('/legal/terms-of-service', function () {
    return view('main');
});

Route::get('/legal/privacy-policy', function () {
    return view('main');
});

Route::get('/legal/dmca', function () {
    return view('main');
});

Route::get('/games', function () {
    return view('main');
});