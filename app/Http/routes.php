<?php


Route::group(['prefix' => 'api', 'middleware' => 'api'], function ()
{
	Route::get('video/current', 'Api\VideoController@current');
	Route::post('video', 'Api\VideoController@create');
	Route::post('video/{video}', 'Api\VideoController@save');
	Route::post('video/{video}/upload', 'Api\VideoController@upload');

	Route::get('settings', 'Api\SettingController@index');
});

Route::controller('/', 'SiteController');
