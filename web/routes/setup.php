<?php

Route::get('/version', 'SetupController@getClientVersion');
Route::get('/versionQTStudio', 'SetupController@getStudioVersion');
Route::get('/{file}', 'SetupController@getFile');

Route::fallback(function () {
    return response('404 not found.', 404)
				->header('Content-Type', 'text/plain');
});