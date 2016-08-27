<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class Controller extends \App\Http\Controllers\Controller {


    public function __construct(Request $request)
    {
    	parent::__construct($request);

        $this->middleware('api');

    	$this->request = $request;
    }

}