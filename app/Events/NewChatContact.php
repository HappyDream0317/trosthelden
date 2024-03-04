<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatContact implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected int $recipient_id;
    public User $contact;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     */
    public function __construct(User $contact, int $recipient_id, int $friend_status = null)
    {
        $this->contact = $contact;
        $this->friend_status = $friend_status;
        $this->recipient_id = $recipient_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('user_'.$this->recipient_id.'_chat_contacts');
    }
}
