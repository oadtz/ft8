<?php

namespace App\Http\Controllers;

use Cookie;
use App\Gif;
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

    public function download(GifService $gifService, $gif, $type = 'gif')
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        if ($type == 'mp4') {
            $mediaPath = public_path('gif/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.mp4');
            if (!file_exists($mediaPath))
                abort(404);

            return response()->download($mediaPath, $gif->_id . '.mp4');
        }

        $mediaPath = public_path('gif/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.gif');
        if (!file_exists($mediaPath))
            abort(404);

        return response()->download($mediaPath, $gif->_id . '.gif');
    }

    public function thumbnail(GifService $gifService, $gif)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        if (!$gif->generateThumbnail())
            abort(404);
        
        return response()->file($gif->outputPath . '/thumbnail.gif'); 
    }

}
