<?php

namespace App\Jobs;

use App\Gif;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GifConvert extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $gif;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Gif $gif)
    {
        //
        $this->gif = $gif;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->gif->status > 0) {
            $this->gif->status = 3; // Processing
            $this->gif->save();

            //
            try {
                $this->gif->process();

                $this->gif->status = 4; // Done

                //$this->gif->cleanUpFiles();
            } catch (\Exception $e) {
                $this->gif->status = 5; // Error
                $this->gif->error = $e->getMessage();
            }

            $this->gif->save();
        }
    }
}
