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

    public function upload(GifService $gifService)
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

    public function viewOnce(GifService $gifService, $gif)
    {
        return $this->view($gifService, $gif, true);
    }

    public function view(GifService $gifService, $gif, $once = false)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        return view('gif.view', compact('gif', 'once'));
    }

    public function download(GifService $gifService, $gif, $type = 'gif')
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        if ($type == 'mp4') {

            if (!Storage::disk(config('_protected.asset_storage'))->exists(config('_protected.asset_folder') . '/mp4/' . $gif->userId . '/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.mp4'))
                abort(404);

            /*return response()->download(Storage::disk(config('_protected.asset_storage'))->get(config('_protected.asset_folder') . '/mp4/' . $gif->_id . '.mp4'), $gif->_id . '.mp4', [
                                    'Content-Description'       =>  'File Transfer',
                                    'Content-Transfer-Encoding' =>  'binary',
                                    'Expires'                   =>  '0',
                                    'Cache-Control'             =>  'must-revalidate, post-check=0, pre-check=0',
                                    'Pragma'                    =>  'public'
                                ]);*/
            return response(Storage::disk(config('_protected.asset_storage'))->get(config('_protected.asset_folder') . '/mp4/' . $gif->userId . '/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.mp4'))
                    ->header('Content-Disposition', ' attachment; filename="' .$gif->_id. '.mp4"')
                    ->header('Content-Type', 'video/mp4')
                    ->header('Content-Description', 'File Transfer')
                    ->header('Content-Transfer-Encoding', 'binary')
                    ->header('Expires', '0')
                    ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->header('Pragma', 'public');
        }

        if (!Storage::disk(config('_protected.asset_storage'))->exists(config('_protected.asset_folder') . '/gif/' . $gif->userId . '/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.gif'))
            abort(404);

        /*return response(Storage::disk(config('_protected.asset_storage'))->get(config('_protected.asset_folder') . '/gif/' . $gif->_id . '.gif'), $gif->_id . '.gif', [
                                    'Content-Description'       =>  'File Transfer',
                                    'Content-Transfer-Encoding' =>  'binary',
                                    'Expires'                   =>  '0',
                                    'Cache-Control'             =>  'must-revalidate, post-check=0, pre-check=0',
                                    'Pragma'                    =>  'public'
                                ]);*/
        return response(Storage::disk(config('_protected.asset_storage'))->get(config('_protected.asset_folder') . '/gif/' . $gif->userId . '/' . $gif->_id . '/' . Gif::OUTPUT_FILE_NAME . '.gif'))
                ->header('Content-Disposition', ' attachment; filename="' .$gif->_id. '.gif"')
                ->header('Content-Type', 'image/gif')
                ->header('Content-Description', 'File Transfer')
                ->header('Content-Transfer-Encoding', 'binary')
                ->header('Expires', '0')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Pragma', 'public');
    }

    public function thumbnail(GifService $gifService, $gif)
    {
        if (!$gif = $gifService->get($gif))
            abort(404);

        //if (!$gif->generateThumbnail())
        //if (!Storage::disk(config('_protected.asset_storage'))->exists(config('_protected.asset_folder') . '/gif/' . $gif->_id . '_thumbnail.gif'))
        //    abort(404);

        return response(Storage::disk(config('_protected.asset_storage'))->get(config('_protected.asset_folder') . '/gif/' . $gif->userId . '/' . $gif->_id . '/thumbnail.gif'))->header('Content-Type', 'image/gif'); 
    }

}
