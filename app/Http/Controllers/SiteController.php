<?php

namespace App\Http\Controllers;

use Cookie;
use MongoDB\BSON\ObjectID as MongoId;
use Illuminate\Http\Request;
use App\Http\Requests;

class SiteController extends Controller
{

    //
    public function index()
    {
        return redirect()->action('GifController@upload');
    }

    public function test()
    {
        return response()->json(\Storage::disk('local')->exists('test/test'));
    }
}
