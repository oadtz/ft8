<?php

namespace App;

use Auth;
use Cookie;
use Image;
use App\Events\GifUpdate;
use MongoDB\BSON\ObjectID as MongoId;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Gif extends BaseModel
{
	use SoftDeletes;

    public static $cacheEnabled = false;
    protected $collection = 'gifs'; 
	protected $fillable = ['settings'];
    const DELETED_AT = 'deletedDateTime';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'status'        =>  0,
        'settings'      =>  [
            'aspectRatio'   =>  0,
            'captionColor'  =>  '#FFFFFF'
        ],
        'input'    		=>  [],
        'output'		=>	[]
    ];
    protected $appends = ['statusName'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($gif) {
            if (Auth::check())
                $gif->userId = Auth::user()->_id;
            else if (Cookie::has('userId'))
                $gif->userId = Cookie::get('userId');
        });

        static::saved(function ($gif) {

            event(new GifUpdate($gif));

        });
    }

    public function setUserIdAttribute($userId)
    {
    	$this->attributes['userId'] = new MongoId($userId);
    }

    public function getStatusNameAttribute()
    {
        return trans('gif.status.' . $this->status);
    }

    public function getInputPathAttribute()
    {
        $path = storage_path('app/public/uploads/' . $this->userId . '/' . $this->_id);

        if (!file_exists($path))
            @mkdir($path, null, true);

        return $path;
    }

    public function getOutputPathAttribute()
    {
        $path = public_path('gif/' . $this->_id);

        if (!file_exists($path))
            @mkdir($path, null, true);

        return $path;
    }

    public function generatePreview($width = null)
    {
    	$ffmpeg = \FFMpeg\FFMpeg::create([
                            'ffmpeg.binaries' => config('app.ffmpeg_bin'),
                            'ffprobe.binaries' => config('app.ffprobe_bin'),
                        ])
                        ->open($this->inputPath . '/input') // extracts streams informations
                        ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))                      // filters video streams
                        ->save($this->inputPath . '/preview.jpg');
    }

    public function generateCaption($w, $h)
    {
        $x = $w/2;
        if ($this->settings['aspectRatio'] == 1) {
            $size = min($w, $h) / 20;

            $y = min($w, $h) * 0.95;
            if ($w < $h)
                $y += ($h - $w) / 2;
        } else {
            $size = $w / 20;

            $y = $h * 0.95;
        }

        $image = Image::canvas($w, $h);
        $image->text($this->settings['caption'], $x, $y, function($font) use($size) {
            $font->file(resource_path('assets/fonts/Kanit-Regular.ttf'));
            $font->size($size);
            $font->color($this->settings['captionColor']);
            $font->align('center');
            $font->valign('bottom');
        });

        if ($image->save($this->inputPath . '/caption.png'))
            return true;

        return false;
    }

    public function process()
    {
        $inputFile = $this->inputPath . '/input';
        $outputFile = $this->outputPath . '/media.gif';

        $input = $this->input;
        $output = $this->output;

        $ffprobe = \FFMpeg\FFProbe::create([
                            'ffmpeg.binaries' => config('app.ffmpeg_bin'),
                            'ffprobe.binaries' => config('app.ffprobe_bin'),
                        ])
                        ->streams($inputFile) // extracts streams informations
                        ->videos()                      // filters video streams
                        ->first();                       // returns the first video stream
        $dimension = $ffprobe->getDimensions();
        $tags = $ffprobe->get('tags');

        $input['width'] = $dimension->getWidth();
        $input['height'] = $dimension->getHeight();
        if (isset($tags['rotate']) && ($tags['rotate'] == 90 || $tags['rotate'] == 270)) {
            list($input['width'], $input['height']) = [$input['height'], $input['width']];
        }

        if ($this->settings['aspectRatio'] == 1) {
            $output['width'] = $output['height'] = min($input['width'], $input['height'], config('site.gif_max_width'));

            if ($input['width'] > $input['height'])
                $scale = '-1:' . $output['height'];
            else
                $scale = $output['width'] . ':-1';
        } else {
            $ratio = min($input['width'], config('site.gif_max_width')) / $input['width'];

            $output['width'] = intval($input['width'] * $ratio);
            $output['height'] = intval($input['height'] * $ratio);

            $scale = $output['width'] . ':-1';
        }

        $this->generateCaption($input['width'], $input['height']);

        $this->cmd = 'ffmpeg -v warning -i '.$this->inputPath.'/input -vf "fps=15,scale=' . $scale . ':flags=lanczos,palettegen" -t ' . config('site.gif_max_time') . ' -y '.$this->inputPath.'/pallette.png;'.
                    'ffmpeg -v warning -i '.$this->inputPath.'/input -i '.$this->inputPath.'/pallette.png  -lavfi "movie='.$this->inputPath.'/caption.png [watermark]; [0:v][watermark] overlay=0:0 [a]; [a] fps=15,scale=' . $scale . ':flags=lanczos [b]; [b][1:v] paletteuse[c]; [c] crop=' . $output['width'] . ':' . $output['height'] . '" -t ' . config('site.gif_max_time') . ' -y '.$this->inputPath.'/output.gif;'.
                    'gifsicle -O3 --lossy=80 -o '.$this->outputPath.'/output.gif '.$this->inputPath.'/output.gif';

        /*$this->cmd = 'ffmpeg -y -i '.$this->inputPath.'/input -i '.$this->inputPath.'/caption.png  -filter_complex "overlay=0:0" -s ' . $output['width'] . 'x' . $output['height'] . ' -t ' . config('site.gif_max_time') . ' -r 10  -f image2 '.$this->inputPath.'/out%03d.png;'.
                    'convert -delay 10 -loop 0 '.$this->inputPath.'/out*.png -coalesce -layers OptimizeTransparency '.$this->inputPath.'/output.gif';*/

        /*$this->cmd = 'ffmpeg -y -i '.$this->inputPath.'/input -i '.$this->inputPath.'/caption.png  -filter_complex "overlay=0:0" -s ' . $output['width'] . 'x' . $output['height'] . ' -t ' . config('site.gif_max_time') . ' -r 10 '.$this->inputPath.'/output.gif;'
                    .'convert '.$this->inputPath.'/output.gif -strip -coalesce -layers Optimize '.$this->outputPath.'/output.gif';*/


        $this->input = $input;
        $this->output = $output;

        $process = new Process($this->cmd);
        $process->setTimeout(60 * 5);
        $process->run();


        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    public function cleanUpFiles()
    {
        $process = new Process('rm -rf ' . $this->inputPath);
        $process->run();

        return $process->isSuccessful();
    }

 }