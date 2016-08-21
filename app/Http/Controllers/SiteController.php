<?php

namespace App\Http\Controllers;

use Cookie;
use MongoDB\BSON\ObjectID;
use Illuminate\Http\Request;
use App\Http\Requests;

class SiteController extends Controller
{

    //
    public function getIndex()
    {
        return redirect()->action('SiteController@getUpload');
    }

    public function getUpload()
    {
    	if (!$userId = $this->request->cookie('userId')) {
    		$userId = (string)new ObjectID();
    	}
    	Cookie::queue(cookie()->forever('userId', $userId));

        return view('upload');
    }
}
