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

	public function current()
	{
		$userId = $this->request->cookie('userId');

		return $this->videoService->getCurrent($userId);
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
		$data = $this->request->all();

		if ($video = $this->videoService->update($video, $data)) {
			$video->status = 1;
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
			if (!file_exists(public_path('uploads/' . $video->userId . '/' . $video->_id))) {
				@mkdir(public_path('uploads/' . $video->userId. '/' . $video->_id));
			}

			$video->fileName = $this->request->file('file')->getClientOriginalName();
			$video->fileExtension = $this->request->file('file')->getClientOriginalExtension();
			$video->fileSize = $this->request->file('file')->getClientSize();
			$video->status = 1;
			
			if ($video->save()) {
				dispatch(new VideoConvert($video));
				
				$this->request->file('file')->move(public_path('uploads/' . $video->userId . '/' . $video->_id), 'in');

				return $video;
			} else {
				return response()->json(false);
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}


}