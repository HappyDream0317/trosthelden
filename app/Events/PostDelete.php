<?php

namespace App\Events;

use App\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post_id;
    public $group_id;

    /**
     * Create a new event instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post_id = $post->id;
        $this->group_id = $post->group_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('group_'.$this->group_id.'_posts');
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return ['id' => $this->post_id];
    }
}
