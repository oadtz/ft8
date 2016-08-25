<?php

namespace App;

use Auth;
use Image;
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
	protected $fillable = ['resolution', 'userId', 'fileName', 'fileExtension', 'fileSize', 'caption', 'captionColor'];
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

    public function generateCaption($w, $h, $text, $color, $rotate = 0)
    {
        if ($rotate == 90 || $rotate == 270)
            list($w, $h) = [$h, $w];

        $text = trim($text);

        if (strlen($text) < 10)
            $size = 240;
        else if (strlen($text) >= 10 && strlen($text) < 30)
            $size = 200;
        else
            $size = 150;

        $image = Image::canvas($w, $h);
        $image->text($text, $w/2, $h-100, function($font) {
            $font->file(resource_path('assets/fonts/Kanit-Regular.ttf'));
            $font->size($size);
            $font->color($color);
            $font->align('center');
            $font->valign('bottom');
        });

        if ($image->save(public_path('uploads/' . $this->userId . '/' . $this->_id . '/caption.png')))
            return true;

        return false;
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

        $tags = $ffprobe->get('tags');
        if (isset($tags['rotate']) && ($tags['rotate'] == 90 || $tags['rotate'] == 270)) {
            list($width, $height) = [$height, $width];
        }

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
        }

        if (!empty($this->caption) && $this->generateOverlay($dimension->getWidth(), $dimension->getHeight(), $this->caption, $this->captionColor, isset($tags['rotate']) ? $tags['rotate'] : 0)) {
            $overlay = '-i ' . public_path('uploads/' . $this->userId . '/' . $this->_id . '/caption.png  -filter_complex "overlay=0:0"');
        } else {
            $overlay = '';
        }

		$this->cmd = sprintf('%s -y -i %s %s -s %sx%s -r 10 %s %s', config('app.ffmpeg_bin'), $inFile, $overlay, $width, $height, $filters, $outFile);

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