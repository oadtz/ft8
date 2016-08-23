<?php


Route::group(['prefix' => 'api', 'middleware' => 'api'], function ()
{
	Route::post('video', 'Api\VideoController@create');
	Route::post('video/{video}', 'Api\VideoController@save');
	Route::post('video/{video}/upload', 'Api\VideoController@upload');
});

Route::controller('/', 'SiteController');
