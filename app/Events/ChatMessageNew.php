<?php

namespace App\Events;

use App\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageNew implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;
    public array $recipients;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     * @param array|null $recipients
     */
    public function __construct(ChatMessage $message, array $recipients = null)
    {
        $this->message = $message;

        if (is_array($recipients) && !empty($recipients)) {
            $this->recipients = $recipients;
        } else {
            $this->recipients = [];
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [new Channel('chat_'.$this->message->chat_id.'_message')];
        foreach ($this->recipients as $recipient) {
            $channels[] = new Channel('user_'.$recipient.'_chat_message');
        }

        return $channels;
    }
}
