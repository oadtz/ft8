<?php

namespace App\Http\Controllers;

use Cookie;
use MongoDB\BSON\ObjectID as MongoId;
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
    		$userId = (string)new MongoId();
    	}
    	Cookie::queue(cookie()->forever('userId', $userId));

        return view('upload');
    }

    public function getTest()
    {
        $video = \FFMpeg\FFMpeg::create([
                            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                            'ffprobe.binaries' => '/usr/bin/ffprobe'
                        ])->open('/var/www/ft8/public/uploads/57b90dd79a892003e67b9924/57bb172f9a8920050f5c4482_src.MP4');
                        $video->filters()
                        ->framerate(new \FFMpeg\Coordinate\FrameRate(15), 15)
                        ->synchronize();
                        $video->save('/var/www/ft8/public/uploads/57b90dd79a892003e67b9924/57bb172f9a8920050f5c4482.gif');

        return 'done';
    }
}
