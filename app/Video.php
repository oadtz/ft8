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
	protected $fillable = ['resolution', 'userId', 'fileName', 'fileExtension', 'fileSize'];
    protected $appends = ['link', 'url'];
    const DELETED_AT = 'deletedDateTime';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'status'        =>  0,
        'resolution'    =>  0
    ];

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

    public function getUrlAttribute()
    {
        return sprintf('uploads/%s/%s/out.gif', $this->userId, (string)$this->_id);
    }

    public function getLinkAttribute()
    {
        return sprintf('uploads/%s/%s.gif', $this->userId, (string)$this->_id);
    }

	public function process()
	{
		$inFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '/in');
		$outFile = public_path('uploads/' . $this->userId . '/' . $this->_id . '/out.gif');
		$filters = '';
		$ffprobe = \FFMpeg\FFProbe::create([
                            'ffmpeg.binaries' => config('app.ffmpeg_bin'),
                            'ffprobe.binaries' => config('app.ffprobe_bin'),
                        ])
                        ->streams($inFile) // extracts streams informations
                        ->videos()                      // filters video streams
                        ->first();                       // returns the first video stream
        $dimension = $ffprobe->getDimensions();
        $width = $dimension->getWidth();
        $height = $dimension->getHeight();

        switch($this->resolution) {
        	case 1: //square
        		if ($width < config('ft8.output_max_width'))
        			$width = ($width >= $height ? $height : $width);
        		else
        			$width = config('ft8.output_max_width');
        		$height = $width;

        		$filters = sprintf('-filter:v "crop=%d:%d"', ($width >= $height ? $height : $width), ($width >= $height ? $height : $width));
        		break;
        	default:
        		if ($width < config('ft8.output_max_width'))
        			$ratio = 1;
        		else
					$ratio = config('ft8.output_max_width') / $width;
        		$width = intval($width * $ratio);
        		$height = intval($height * $ratio);

                $tags = $ffprobe->get('tags');
                if (isset($tags['rotate']) && ($tags['rotate'] == 90 || $tags['rotate'] == 270)) {
                    list($width, $height) = [$height, $width];
                }
        }

		$this->cmd = sprintf('%s -y -i %s -s %sx%s -r 10 %s %s', config('app.ffmpeg_bin'), $inFile, $width, $height, $filters, $outFile);

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