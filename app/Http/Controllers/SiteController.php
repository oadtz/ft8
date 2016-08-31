<?php

namespace App\Http\Controllers;

use Cookie;
use App\Services\GifService;
use MongoDB\BSON\ObjectID as MongoId;
use Illuminate\Http\Request;
use App\Http\Requests;

class SiteController extends Controller
{

    //
    public function index()
    {
        $response = redirect()->action('GifController@upload');

        return $response;

        //return redirect()->action('SiteController@create')->with('gif', $gif); // Not work for redirect
    }

    public function create(GifService $gifService)
    {
    	return view('create');
    }

    public function test()
    {
    	Storage::disk('public')->put('public/gif/57b90dd79a892003e67b9924/57c6e75d9a892005f73bab29/thumbnail.gif', 'wdsasdf');
    }
}
