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

Route::get('gif/{gif}/download/{type?}', 'GifController@download');
Route::get('gif/{gif}.html', 'GifController@view');
//Route::get('gif/{gif}/thumbnail.gif', 'GifController@thumbnail');
Route::get('gif/upload', 'GifController@upload');
Route::get('gif/{gif}/generate', 'GifController@generate');
Route::get('/', 'SiteController@index');
Route::get('test', function () {

	\Storage::disk('s3')
			->put('aaaa/aaaa/test.txt', 'test', 'public');


	return response(\Storage::disk('s3')->url('aaaa/aaaa/test.txt'));

});
