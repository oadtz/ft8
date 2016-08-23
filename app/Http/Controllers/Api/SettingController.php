<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class SettingController extends Controller {

	public function __construct(Request $request)
	{
		parent::__construct($request);

        $this->middleware('auth', [ 'except'  =>  [ 'index' ] ]);
	}

	public function index()
	{
		return response()->json(config('ft8'));
	}

}