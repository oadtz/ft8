<?php

namespace App\Services;

use Validator;

class BaseService {

	public function validate($data, $rules, $messages = array())
	{
		$validator = Validator::make($data, $rules, $messages);

		if($validator->fails()) 
			throw new \App\Exceptions\Validation($validator);
		
		return true;
	}
	
}