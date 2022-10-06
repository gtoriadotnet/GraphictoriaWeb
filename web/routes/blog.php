<?php

Route::get('/', 'BlogController@home')->name('home');

Route::fallback(function () {
    return response(view('blog.404'), 404);
});