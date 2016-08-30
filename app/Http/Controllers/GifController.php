<?php

namespace App\Http\Controllers;

use Cookie;
use Storage;
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

            if (!Storage::disk(env('ASSET_STORAGE'))->exists(env('ASSET_FOLDER') . '/mp4/' . $gif->_id . '.mp4'))
                abort(404);

            return response()->download(Storage::disk(env('ASSET_STORAGE'))->get(env('ASSET_FOLDER') . '/mp4/' . $gif->_id . '.mp4'), $gif->_id . '.mp4', [
                                    'Content-Description'       =>  'File Transfer',
                                    'Content-Transfer-Encoding' =>  'binary',
                                    'Expires'                   =>  '0',
                                    'Cache-Control'             =>  'must-revalidate, post-check=0, pre-check=0',
                                    'Pragma'                    =>  'public'
                                ]);
        }

        if (!Storage::disk(env('ASSET_STORAGE'))->exists(env('ASSET_FOLDER') . '/gif/' . $gif->_id . '.gif'))
            abort(404);

        return response()->download(Storage::disk(env('ASSET_STORAGE'))->get(env('ASSET_FOLDER') . '/gif/' . $gif->_id . '.gif'), $gif->_id . '.gif', [
                                    'Content-Description'       =>  'File Transfer',
                                    'Content-Transfer-Encoding' =>  'binary',
                                    'Expires'                   =>  '0',
                                    'Cache-Control'             =>  'must-revalidate, post-check=0, pre-check=0',
                                    'Pragma'                    =>  'public'
                                ]);
    }

    /*public function thumbnail(GifService $gifService, $gif)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        if (!$gif->generateThumbnail())
            abort(404);
        
        return response()->file(Storage::disk(env('ASSET_STORAGE'))->get(env('ASSET_FOLDER') . '/gif/' . $gif->_id . '_thumbnail.gif')); 
    }*/

}
