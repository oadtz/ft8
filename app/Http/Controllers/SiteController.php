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

    /*public function getTest()
    {
        $image = \Image::canvas(320, 180);

        $text = 'ทดสอบ';

        $x = strlen($text);

        $image->text($text, 160, 160, function($font) {
            $font->file(resource_path('assets/fonts/Kanit-Regular.ttf'));
            $font->size(24 );
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
        });

        //$image->save(public_path('uploads/overlay.png'));

        return $image->response();
    }*/
}
