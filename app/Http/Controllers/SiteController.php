<?php

namespace App\Http\Controllers;

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
        return view('upload');
    }
}
