<?php

namespace App\Services;

use App\Video;
use MongoDB\BSON\ObjectID as MongoId;

class VideoService extends BaseService {

    public function get($id)
    {
        return Video::find($id);
    }

    public function store($data)
    {
    	return Video::create($data);
    }

    public function update($id, $data)
    {
        if (!$video = Video::where('_id', new MongoId((string)$id))->first())
            abort(404);

        $video->fill($data);

        $video->save();

        return $video;
    }

}