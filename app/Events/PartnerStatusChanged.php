<?php

namespace App\Events;

use App\UserFriend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartnerStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $foreign_user_id;
    public $status;

    public function __construct($userId, $foreignUserId, $status)
    {
        $this->user_id = (int) $userId;
        $this->foreign_user_id = (int) $foreignUserId;
        $this->status = (int) $status;
    }

    /**
     * Boradcast to Friendship Accepted Channel
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('user_'.$this->user_id.'_partner_status_changed'),
            new Channel('user_'.$this->foreign_user_id.'_partner_status_changed'),
        ];
    }
}
