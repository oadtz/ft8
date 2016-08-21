<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller {


    public function __construct(Request $request)
    {
    	parent::__construct($request);

        $this->middleware('auth');

    	$this->request = $request;
    }

	protected function getQuery($data = array())
	{
		return array_merge((array)$this->request->input('q'), $data);
	}

	protected function getSearch($data = array())
	{
		return array_merge((array)$this->request->input('s'), $data);
	}

	protected function getOrder($data = array())
	{
		return array_merge((array)$this->request->input('o'), $data);
	}

	protected function getPaging($data = array())
	{
		return array_merge((array)$this->request->input('p'), $data);
	}

}