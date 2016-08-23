<?php

namespace App\Jobs;

use App\Video;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VideoConvert extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        //
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->video->status > 0) {
            $this->video->status = 2;
            $this->video->save();

            //
            try {
                $this->video->process();

                $this->video->status = 3;
            } catch (\Exception $e) {
                $this->video->status = 4;
                $this->video->errorMessages = $e->getMessage();
            }

            $this->video->save();
        }
    }
}
