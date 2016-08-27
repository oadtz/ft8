<?php
namespace App\Http\Controllers\Api;

use Auth;
use Image;
use Mail;
use Response;
use App\Jobs\GifConvert;
use App\Services\GifService;
use Illuminate\Http\Request;

class GifController extends Controller {

	public function __construct(Request $request, GifService $gifService)
	{
		parent::__construct($request);

		$this->gifService = $gifService;
	}

	public function create()
	{
		$data = [];
		if (!Auth::check() && empty($this->request->cookie('userId')))
			abort(401);


		$gif = $this->gifService->store($data);

		return $gif;
	}

	public function upload($gif)
	{
		if (!$this->request->hasFile('file'))
			abort(403);

		if (!$gif = $this->gifService->get($gif))
			abort(404);

		try {
			$file = $this->request->file('file');
			$gif->input = [
				'fileName'		=>	$file->getClientOriginalName(),
				'mimeType'		=>	$file->getClientMimeType(),
				'size'			=>	$file->getClientSize()
			];
			$gif->status = 1;

			$file->move($gif->inputPath, 'input');
			$gif->generatePreview();

			if ($gif->save()) {

				return $gif;

			} else {
				abort(500, 'Cannot save video to database.');
			}
		} catch (\Exception $e) {
			abort(500, $e->getMessage());
		}
	}

	public function generate($gif)
	{
		if (!$gif = $this->gifService->get($gif))
			abort(404);

		if (!file_exists($gif->inputPath . '/input'))
			abort(404);

		try {
			$gif->settings = $this->request->input('settings');
			$gif->status = 2; // In Queue
			$gif->save();

			dispatch(new GifConvert($gif));
			//$gif->settings = $this->request->input('settings');

			//$gif->process();

			//$gif->save();
			return $gif;
		} catch (\Exception $e) {
			$gif->status = 5; // Error
			$gif->error = $e->getMessages();
			$gif->save();
			abort(500, $gif->error);
		}
	}

	public function previewUpload($gif)
	{
		if (!$gif = $this->gifService->get($gif))
			abort(404);

		if (!file_exists($gif->inputPath . '/preview.jpg'))
			abort(404);

		$image = Image::make($gif->inputPath . '/preview.jpg');

		if ($this->request->input('aspect') == 1) {
			$w = $image->width();
			$h = $image->height();

			if ($w > $h)
				$image->resize(null, config('site.gif_max_width'), function ($constraint) {
				    $constraint->aspectRatio();
				});
			else
				$image->resize(config('site.gif_max_width'), null, function ($constraint) {
				    $constraint->aspectRatio();
				});

			$image->resizeCanvas(min($w, $h, config('site.gif_max_width')), min($w, $h, config('site.gif_max_width')));
		} else {
			$image->resize(config('site.gif_max_width'), null, function ($constraint) {
			    $constraint->aspectRatio();
			});
		}

		return $image->response('jpg', 60);
	}

	public function previewPlaceholder($gif)
	{
		if (!$gif = $this->gifService->get($gif))
			abort(404);

		if (!file_exists($gif->inputPath . '/preview.jpg'))
			abort(404);

		$image = Image::make($gif->inputPath . '/preview.jpg');
		$w = $image->width();
		$h = $image->height();

		if ($this->request->input('aspect') == 1) {
			$image = Image::canvas(min($w, $h, config('site.gif_max_width')), min($w, $h, config('site.gif_max_width')));
		} else {
			$image = Image::canvas($w, $h);

			$image->resize(config('site.gif_max_width'), null, function ($constraint) {
			    $constraint->aspectRatio();
			});
		}

		return $image->response('png', 10);
	}

}