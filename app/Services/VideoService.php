<?php

namespace App\Services;

use MongoId;
use App\Video;

class VideoService extends BaseService {


    public function store($data)
    {
    	return Video::create($data);
    }

}