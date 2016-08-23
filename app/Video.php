<?php

namespace App;

use Auth;
use App\Events\VideoUpdate;
use App\Services\StationService;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Video extends BaseModel
{
	const FFMPEG_CMD = [
		'mp4'			=>	'ffmpeg -y -i %s -an -vcodec libx264 -crf 23 -s %s %s',
		'mov'			=>	'ffmpeg -y -i %s -acodec copy -vcodec copy -f mov -s %s %s',
		'gif'			=>	'ffmpeg -y -i %s -s %s -r 15 %s',
		'wmv'			=>	'ffmpeg -y -sameq -i %s -s %s %s'
	];
    public static $cacheEnabled = false;
    protected $collection = 'videos'; 
	protected $fillable = ['resolution', 'format', 'userId', 'fileName', 'fileExtension', 'fileSize', 'overlay'];

	public static function boot()
	{
		parent::boot();

		static::creating(function ($video) {
			$video->status = 0;
		});

        static::saved(function ($video) {
            event(new VideoUpdate($video));
        });
	}

	public function process()
	{
		$ext = $this->fileExtension;
		$inFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '_src.' . $ext);
		foreach (config('ft8.video_formats') as $format) {
			if ($format['format'] == $this->format) {
				$ext = $format['ext'];
			}
		}

		$outFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '.' . $ext);

		$this->cmd = sprintf(static::FFMPEG_CMD[$this->format], $inFile, $this->resolution, $outFile);

		if (!empty($this->overlay)) {
			$this->cmd .= ' -i ' . public_path('uploads/' . $this->userId . '/' . $this->_id . '_overlay') . ' -filter_complex "[0:v]overlay=(W-w)/2:(H-h)/2"'; 
		}

		$process = new Process($this->cmd);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		return $process->getOutput();
	}

}