<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GeneratePDF extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $type;
    /**
     * @var array
     */
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @param       $type
     * @param array $attributes
     */
    public function __construct($type, $attributes = [])
    {
        $this->type = $type;
        $this->attributes = $attributes;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
