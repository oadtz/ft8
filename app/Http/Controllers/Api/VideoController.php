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

	public function create()
	{
		$data = $this->request->all();
		$data['userId'] = $this->request->cookie('userId');

		$video = $this->videoService->store($data);

		return $video;
	}

	public function save($video)
	{
		$data = $this->request->input('video');

		if ($this->request->hasFile('file')) {
			$this->request->file('file')->move(public_path('uploads/' . $data['userId']), $data['_id'] . '_overlay');
		}

		if ($video = $this->videoService->update($video, $data)) {
			dispatch(new VideoConvert($video));
		}
		return response()->json($video);
	}

	public function upload($video)
	{
		if (!$this->request->hasFile('file'))
			abort(403, trans('error.empty_file'));

		if (!$video = $this->videoService->get($video))
			abort(404);

		try {
			if (!file_exists(public_path('uploads/' . $video->userId))) {
				@mkdir(public_path('uploads/' . $video->userId));
			}

			$video->fileName = $this->request->file('file')->getClientOriginalName();
			$video->fileExtension = $this->request->file('file')->getClientOriginalExtension();
			$video->fileSize = $this->request->file('file')->getClientSize();
			
			if ($video->save()) {
				dispatch(new VideoConvert($video));
				
				$this->request->file('file')->move(public_path('uploads/' . $video->userId), $video->_id . '_src.' . $video->fileExtension);

				return $video;
			} else {
				return response()->json(false);
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


}