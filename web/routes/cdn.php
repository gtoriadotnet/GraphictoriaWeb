<?php

Route::get('/{hash}', 'CdnController@getContent')->name('content');

Route::fallback(function () {
    return response('404 not found.', 404)
				->header('Content-Type', 'text/plain');
});