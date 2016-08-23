<?php

namespace App;

use Auth;
use App\Events\VideoUpdate;
use App\Services\StationService;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Video extends BaseModel
{
	use SoftDeletes;

    public static $cacheEnabled = false;
    protected $collection = 'videos'; 
	protected $fillable = ['resolution', 'format', 'userId', 'fileName', 'fileExtension', 'fileSize'];

    const DELETED_AT = 'deletedDateTime';
    protected $dates = ['deleted_at'];

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
		$inFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '/in');
		$outFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '/out.gif');
		$dimension = \FFMpeg\FFProbe::create([
                            'ffmpeg.binaries' => config('app.ffmpeg_bin'),
                            'ffprobe.binaries' => config('app.ffprobe_bin'),
                        ])
                        ->streams($inFile) // extracts streams informations
                        ->videos()                      // filters video streams
                        ->first()                       // returns the first video stream
                        ->getDimensions();

        switch($this->resolution) {
        	case 1: //smaller
        		$width = intval($dimension->getWidth() * 0.8);
        		$height = intval($dimension->getHeight() * 0.8);
        		break;
        	case 2: //smallest
        		$width = intval($dimension->getWidth() * 0.5);
        		$height = intval($dimension->getHeight() * 0.5);
        		break;
        	case 3: //square
        		$width = $dimension->getWidth() >= $dimension->getHeight() ? $dimension->getHeight() : $dimension->getWidth();
        		$height = $width;
        		break;
        	default:
        		$width = $dimension->getWidth();
        		$height = $dimension->getHeight();
        }

		$this->cmd = sprintf('%s -y -i %s -s %sx%s -r 15 %s', config('app.ffmpeg_bin'), $inFile, $width, $height, $outFile);

		/*if (!empty($this->overlay)) {
			$this->cmd .= ' -i ' . public_path('uploads/' . $this->userId . '/' . $this->_id . '_overlay') . ' -filter_complex "[0:v]overlay=(W-w)/2:(H-h)/2"'; 
		}*/

		$process = new Process($this->cmd);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		return $process->getOutput();
	}

}