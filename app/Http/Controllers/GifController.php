<?php

namespace App\Http\Controllers;

use Cookie;
use App\Services\GifService;
use MongoDB\BSON\ObjectID as MongoId;
use Illuminate\Http\Request;
use App\Http\Requests;

class GifController extends Controller
{


    public function upload()
    {
    	if (!$userId = $this->request->cookie('userId')) {
    		$userId = (string)new MongoId();
    	}
    	Cookie::queue(cookie()->forever('userId', $userId));

        return view('gif.upload');
    }

    public function generate(GifService $gifService, $gif)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        return view('gif.generate', compact('gif'));
    }

    public function view(GifService $gifService, $gif)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        return view('gif.view', compact('gif'));
    }

}
