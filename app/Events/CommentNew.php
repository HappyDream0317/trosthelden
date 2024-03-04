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

class CommentNew implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var PostComment
     */
    public $comment;

    /**
     * Create a new event instance.
     *
     * @param PostComment $comment
     */
    public function __construct(PostComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('group_'.$this->comment->post->group_id.'_post_'.$this->comment->post->id.'_comments');
    }
}
