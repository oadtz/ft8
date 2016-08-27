<?php

namespace App\Events;

use App\Gif;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GifUpdate extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $gif;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Gif $gif)
    {
        //
        $this->gif = $gif;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['gif.' . $this->gif->_id];
    }

    public function broadcastAs()
    {
        return 'updated';
    }
}
