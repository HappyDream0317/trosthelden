<?php

namespace App\Events;

use App\PostComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostCommentDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group_id;
    public $post_id;
    public $comment_id;

    /**
     * Create a new event instance.d.
     *
     * @param PostComment $postComment
     */
    public function __construct(PostComment $postComment)
    {
        $this->group_id = $postComment->post->group_id;
        $this->post_id = $postComment->post_id;
        $this->comment_id = $postComment->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('group_'.$this->group_id.'_post_'.$this->post_id.'_comments');
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return ['id' => $this->comment_id];
    }
}
