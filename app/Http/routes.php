<?php


Route::group(['prefix' => 'api', 'middleware' => 'api'], function ()
{
	Route::post('video/upload', 'Api\VideoController@upload');
});

Route::controller('/', 'SiteController');
