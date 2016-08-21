<?php

namespace App;

use Auth;
use MongoId;
use App\Events\VideoUpdate;
use App\Services\StationService;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Video extends BaseModel
{

	const FFMPEG_CMD = [
		'h264'				=>	'ffmpeg -i %s -c:v libx264 -preset slow -crf 22 crop=%s -s %s -c:a copy %s',
		'photojpeg'			=>	'ffmpeg -y -probesize 5000000 -f image2 -r 48 -force_fps -i %s -c:v mjpeg -qscale:v 1 -vendor ap10 -pix_fmt yuvj422p crop=%s -s %s -r 48 %s',
		'gif'				=>	'ffmpeg -i %s -vf crop=%s -s %s -r 10 %s.gif -y'
	];
    public static $cacheEnabled = false;
    protected $collection = 'videos'; 
	protected $fillable = ['resolution', 'format', 'file_id'];

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
		$inFile = public_path('uploads/' . $this->file_id . '/src');
		$outFile = public_path('uploads/' . $this->file_id . '/out');

		$cmd = sprintf(static::FFMPEG_CMD[$this->format], $inFile, str_replace('x', ':', $this->resolution), $this->resolution, $outFile);

		$process = new Process($cmd);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		return $process->getOutput();
	}

}