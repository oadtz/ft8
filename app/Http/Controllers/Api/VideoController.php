<?php
namespace App\Http\Controllers\Api;

use Auth;
use Mail;
use Response;
use App\Jobs\VideoConvert;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller {

	public function __construct(Request $request, VideoService $videoService)
	{
		parent::__construct($request);

        $this->middleware('auth', [ 'only'  =>  [ 'void' ] ]);

		$this->videoService = $videoService;
	}

	public function void()
	{

	}

	public function upload()
	{
		if (!$this->request->hasFile('file'))
			abort(403, trans('error.empty_file'));

		try {

			$userId = $this->request->cookie('userId');
			if (!file_exists(public_path('uploads/' . $userId))) {
				@mkdir(public_path('uploads/' . $userId));
			}

			$this->request->file('file')->move(public_path('uploads/' . $userId), 'src');

			$data = $this->request->input('video');
			$data['file_id'] = $this->request->cookie('userId');
			$video = $this->videoService->store($data);

			dispatch(new VideoConvert($video));

			return $video;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


}