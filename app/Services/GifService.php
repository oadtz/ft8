<?php

namespace App\Services;

use App\Gif;
use MongoDB\BSON\ObjectID as MongoId;

class GifService extends BaseService {

    public function get($id)
    {
        return Gif::find($id);
    }

    public function store($data)
    {
    	return Gif::create($data);
    }

    public function update($id, $data)
    {
        if (!$gif = Gif::where('_id', new MongoId($id))->first())
            abort(404);

        $gif->fill($data);

        $gif->save();

        return $gif;
    }

    public function getCurrent($userId)
    {
        return Gif::where('userId', new MongoId($userId))->where('status', '>', 0)->orderBy('createdDateTime', 'desc')->first();
    }

}