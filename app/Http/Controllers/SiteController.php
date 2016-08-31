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
    public function index($gif = null)
    {
        return redirect()->action('GifController@upload')->with('gif', $gif);

        //return redirect()->action('SiteController@create')->with('gif', $gif);
    }

    public function create(GifService $gifService)
    {
    	$gif = $gifService->get(session('gif'));

    	return view('create', compact('gif'));
    }

    public function test()
    {
    	Storage::disk('public')->put('public/gif/57b90dd79a892003e67b9924/57c6e75d9a892005f73bab29/thumbnail.gif', 'wdsasdf');
    }
}
