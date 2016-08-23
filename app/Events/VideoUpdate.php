<?php

namespace App\Events;

use App\Video;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VideoUpdate extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $video;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        //
        $this->video = $video;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['video.' . $this->video->_id];
    }

    public function broadcastAs()
    {
        return 'updated';
    }
}
