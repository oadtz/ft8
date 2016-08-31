<?php

namespace App;

use Auth;
use Cookie;
use Image;
use Storage;
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
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'status'        =>  0,
        'settings'      =>  [
            'aspectRatio'   =>  0,
            'caption'       =>  '',
            'captionColor'  =>  '#FFFFFF'
        ],
        'input'    		=>  [],
        'output'		=>	[]
    ];
    protected $appends = ['statusName', 'url', 'gifUrl', 'thumbnailUrl', 'videoUrl'];
    const DELETED_AT = 'deletedDateTime';
    const OUTPUT_FILE_NAME = 'media';

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

    public function getUrlAttribute()
    {
        return asset('gif/' . $this->_id . '.html');
    }

    public function getGifUrlAttribute()
    {
        //return asset('gif/' . $this->_id . '/' . static::OUTPUT_FILE_NAME . '.gif');
        return Storage::disk(config('_protected.asset_storage'))->url(config('_protected.asset_folder') . '/gif/' . $this->userId . '/' . $this->_id . '/' . static::OUTPUT_FILE_NAME . '.gif');
    }

    public function getThumbnailUrlAttribute()
    {
        //return asset('gif/' . $this->_id . '/thumbnail.gif');
        return Storage::disk(config('_protected.asset_storage'))->url(config('_protected.asset_folder') . '/gif/' . $this->userId . '/' . $this->_id . '/thumbnail.gif');
    }

    public function getVideoUrlAttribute()
    {
        //return asset('gif/' . $this->_id . '/' . static::OUTPUT_FILE_NAME . '.mp4');
        return Storage::disk(config('_protected.asset_storage'))->url(config('_protected.asset_folder') . '/mp4/' . $this->userId . '/' . $this->_id . '/' . static::OUTPUT_FILE_NAME . '.mp4');
    }

    public function getStatusNameAttribute()
    {
        return trans('gif.status.' . $this->status);
    }

    public function getInputPathAttribute()
    {
        $path = storage_path('app/in/' . $this->_id);

        if (!file_exists($path)) {
            @mkdir($path, 0755, true);
        }

        return $path;
    }

    public function getOutputPathAttribute()
    {
        $path = storage_path('app/out/' . $this->_id);

        if (!file_exists($path))
            @mkdir($path, 0755, true);

        return $path;
    }

    public function generatePreview($width = null)
    {
    	$ffmpeg = \FFMpeg\FFMpeg::create([
                            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                            'ffprobe.binaries' => '/usr/bin/ffprobe',
                        ])
                        ->open($this->inputPath . '/input') // extracts streams informations
                        ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))                      // filters video streams
                        ->save($this->inputPath . '/preview.jpg');
    }

    public function generateThumbnail()
    {
        if (!file_exists($this->outputPath. '/' . static::OUTPUT_FILE_NAME . '.mp4'))
            abort(404);

        $output = $this->output;
        if ($output['width'] < $output['height']) {
            $pad = ceil($output['height']*$output['thumbnailScale']) . ':ih:(ow-iw)/2:0';
            $output['thumbnailWidth'] = $output['thumbnailHeight'] = ceil($output['height']*$output['thumbnailScale']);
        } else {
            $pad = 'iw:' . ceil($this->output['width']*$this->output['thumbnailScale']) . ':0:(oh-ih)/2';
            $output['thumbnailWidth'] = $output['thumbnailHeight'] = ceil($this->output['width']*$this->output['thumbnailScale']);
        }

        $this->cmd = 'ffmpeg -v warning -i '.$this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.mp4 -vf "scale='.ceil($this->output['width']*config('site.gif_thumbnail_scale')).':'.ceil($this->output['height']*config('site.gif_thumbnail_scale')).':flags=lanczos,palettegen"  -y '.$this->inputPath.'/pallette.png;'.
                    'ffmpeg -v warning -i '.$this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.mp4 -i '.$this->inputPath.'/pallette.png  -lavfi "scale='.ceil($this->output['width']*$this->output['thumbnailScale']).':'.ceil($this->output['height']*$this->output['thumbnailScale']).':flags=lanczos,pad='.$pad.':color=black [a]; [a][1:v] paletteuse" -y '.$this->inputPath.'/output.gif;'.
                    'gifsicle -O1 --lossy=80 -o '.$this->outputPath.'/thumbnail.gif '.$this->inputPath.'/output.gif';

        $this->output = $output;

        //$this->cmd = 'gifsicle -O1 --lossy=120 --scale '.config('site.gif_thumbnail_scale').' -o '.$this->outputPath.'/thumbnail.gif '.$this->outputPath.'/' . static::OUTPUT_FILE_NAME . '.gif;';

        $process = new Process($this->cmd);
        $process->setTimeout(60);
        $process->run();


        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->store($this->outputPath.'/thumbnail.gif', '/gif/' . $this->userId . '/' . $this->_id, 'thumbnail.gif');

        return true;
    }

    public function generateGif()
    {
        if (!file_exists($this->outputPath. '/' . static::OUTPUT_FILE_NAME . '.mp4'))
            abort(404);

        $this->cmd = 'ffmpeg -v warning -i '.$this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.mp4 -vf "scale='.$this->output['width'].':'.$this->output['height'].':flags=lanczos,palettegen"  -y '.$this->inputPath.'/pallette.png;'.
                    'ffmpeg -v warning -i '.$this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.mp4 -i '.$this->inputPath.'/pallette.png  -lavfi "scale='.$this->output['width'].':'.$this->output['height'].':flags=lanczos [a]; [a][1:v] paletteuse" -y '.$this->inputPath.'/output.gif;'.
                    'gifsicle -O3 --lossy=80 -o '.$this->outputPath.'/' . static::OUTPUT_FILE_NAME . '.gif '.$this->inputPath.'/output.gif';

        $process = new Process($this->cmd);
        $process->setTimeout(60 * 5);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }


        $this->store($this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.gif', '/gif/' . $this->userId . '/' . $this->_id, static::OUTPUT_FILE_NAME.'.gif');

        return true;
    }

    public function generateCaption($w, $h, $aspectRatio, $text, $textColor)
    {
        $fontFile = resource_path('assets/fonts/Kanit-Regular.ttf');

        $x = $w/2;
        if ($aspectRatio == 1) {
            $size = min($w, $h) / 15;

            $y = min($w, $h) * 0.95;
            if ($w < $h)
                $y += ($h - $w) / 2;
        } else {
            $size = $w / 15;

            $y = $h * 0.95;
        }

        $image = Image::canvas($w, $h);

        $image->text($text, $x + 2, $y + 2, function($font) use($fontFile, $size) {
            $font->file($fontFile);
            $font->size($size);
            $font->color('#000000'); // Glow color
            $font->align('center');
            $font->valign('bottom');
        });

        $image->text($text, $x, $y, function($font) use($fontFile, $size, $textColor) {
            $font->file($fontFile);
            $font->size($size);
            $font->color($textColor);
            $font->align('center');
            $font->valign('bottom');
        });

        if (!$image->save($this->inputPath . '/caption.png'))
            return false;

        return true;
    }

    public function process()
    {
        $inputFile = $this->inputPath . '/input';
        $outputFile = $this->outputPath . '/media.gif';

        $input = $this->input;
        $output = $this->output;

        $ffprobe = \FFMpeg\FFProbe::create([
                            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                            'ffprobe.binaries' => '/usr/bin/ffprobe',
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
            $ratio = min($input['width'], $input['height'], config('site.gif_max_width')) / max($input['width'], $input['height']);

            $output['width'] = ceil($input['width'] * $ratio);
            $output['height'] = ceil($input['height'] * $ratio);

            $scale = $output['width'] . ':-1';
        }

        /*if (max($this->output['thumbnailWidth'], $this->output['thumbnailHeight']) > config('site.gif_thumbnail_width'))
            $output['thumbnailWidth'] = $output['thumbnailHeight'] = config('site.gif_thumbnail_width');
        else {
            $output['thumbnailWidth'] = max($this->output['thumbnailWidth'], $this->output['thumbnailHeight']);
            $output['thumbnailHeight'] = max($this->output['thumbnailWidth'], $this->output['thumbnailHeight']);
        }*/
        if (max($output['width'], $output['height']) > config('site.gif_thumbnail_width')) {
            $output['thumbnailScale'] = config('site.gif_thumbnail_width')/max($output['width'], $output['height']);
        } else {
            $output['thumbnailScale'] = 1;
        }

        $this->generateCaption(
                    $input['width'], 
                    $input['height'], 
                    isset($this->settings['aspectRatio']) ? $this->settings['aspectRatio'] : 0,
                    isset($this->settings['caption']) ? $this->settings['caption'] : config('site.gif_default_caption'),
                    isset($this->settings['captionColor']) ? $this->settings['captionColor'] : config('site.gif_default_caption_color')
                );

        /*$this->cmd = 'ffmpeg -v warning -i '.$this->inputPath.'/input -vf "fps=10,scale=' . $scale . ':flags=lanczos,palettegen" -t ' . config('site.gif_max_time') . ' -y '.$this->inputPath.'/pallette.png;'.
                    'ffmpeg -v warning -i '.$this->inputPath.'/input -i '.$this->inputPath.'/pallette.png  -lavfi "movie='.$this->inputPath.'/caption.png [watermark]; [0:v][watermark] overlay=0:0 [a]; [a] fps=10,scale=' . $scale . ':flags=lanczos [b]; [b][1:v] paletteuse[c]; [c] crop=' . $output['width'] . ':' . $output['height'] . '" -t ' . config('site.gif_max_time') . ' -y '.$this->inputPath.'/output.gif;'.
                    'gifsicle -O3 --lossy=150 -o '.$this->outputPath.'/' . static::OUTPUT_FILE_NAME . '.gif '.$this->inputPath.'/output.gif';*/
        $this->cmd = 'ffmpeg -v warning -i '.$this->inputPath.'/input -movflags faststart -pix_fmt yuv420p -an -lavfi "movie='.$this->inputPath.'/caption.png [watermark]; [0:v][watermark] overlay=0:0 [a]; [a] fps=' . config('site.gif_framerate') . ',scale=' . $scale . ' [b]; [b] crop=' . $output['width'] . ':' . $output['height'] . '" -t ' . config('site.gif_max_time') . ' -y '.$this->outputPath.'/'. static::OUTPUT_FILE_NAME .'.mp4;';


        $this->input = $input;
        $this->output = $output;

        $process = new Process($this->cmd);
        $process->setTimeout(60 * 5);
        $process->run();


        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->store($this->outputPath.'/'.static::OUTPUT_FILE_NAME.'.mp4', '/mp4/' . $this->userId . '/' . $this->_id, static::OUTPUT_FILE_NAME.'.mp4');

        return true;
    }

    public function cleanUpFiles()
    {
        $process = new Process('rm -rf ' . $this->inputPath . '; rm -rf ' . $this->outputPath);
        $process->run();

        return $process->isSuccessful();
    }

 }