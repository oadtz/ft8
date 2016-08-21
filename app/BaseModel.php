<?php

namespace App;

use Auth;
use Cache;
use Carbon\Carbon;
use MongoId;
use MongoRegex;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent {
    public $timestamps = true;
	public static $cacheEnabled = true;

    const CREATED_AT = 'createdDateTime';
    const UPDATED_AT = 'modifiedDateTime';

    
    public static function boot()
    {
    	parent::boot();

    	static::saving(function ($obj)
    	{
			if ($obj->timestamps) {
				$obj->modifiedUser = [
					'_id'		=>	Auth::check() ? new MongoId((string)Auth::user()->_id) : null,
					'login'		=>	Auth::check() ? Auth::user()->login : null
				];
			}
    	});

    	static::creating(function ($obj)
    	{
			if ($obj->timestamps) {
				$obj->createdUser = [
					'_id'		=>	Auth::check() ? new MongoId((string)Auth::user()->_id) : null,
					'login'		=>	Auth::check() ? Auth::user()->login : null
				];
			}
    	});
    }

}