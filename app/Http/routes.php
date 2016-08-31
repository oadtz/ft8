<?php


Route::group(['prefix' => 'api', 'middleware' => 'api'], function ()
{
	//Route::get('video/current', 'Api\VideoController@current');
	//Route::post('video', 'Api\VideoController@create');
	//Route::post('video/{video}', 'Api\VideoController@save');
	//Route::post('video/{video}/upload', 'Api\VideoController@upload');

	Route::get('settings', 'Api\SettingController@index');
	//Route::get('settings/info', 'Api\SettingController@info');

	Route::post('gif/{gif}/upload', 'Api\GifController@upload');
	Route::get('gif/{gif}/upload/preview', 'Api\GifController@previewUpload');
	Route::post('gif/{gif}/generate', 'Api\GifController@generate');
	Route::post('gif', 'Api\GifController@create');
});
Route::get('test', function () {


    	Storage::disk('public')->put('public/gif/57b90dd79a892003e67b9924/57c6e75d9a892005f73bab29/thumbnail.gif', 'wdsasdf', 'public');

});

Route::get('gif/upload/{referer?}', 'GifController@upload');
Route::get('gif/{gif}/download/{type?}', 'GifController@download');
Route::get('gif/{gif}.html', 'GifController@view');
Route::get('gif/{gif}/thumbnail.gif', 'GifController@thumbnail');
Route::get('gif/{gif}/generate', 'GifController@generate');
//Route::get('create', 'SiteController@create');
Route::get('/', 'SiteController@index');
