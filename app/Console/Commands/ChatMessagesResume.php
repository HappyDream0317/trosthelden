<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\Helpers\UserNotifications;
use App\Mail\ChatMessageResumeEmail;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ChatMessagesResume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat-messages:resume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resume of all chat messages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userCount = 0;
        User::where('last_seen_at', '>', Carbon::now()->subDays(14))
            ->get()
            ->each(function (User $user) use (&$userCount) {
                if ($user->canSendNotification(UserNotifications::CHAT_MESSAGE)) {
                    $newMessageCount = $user->getNewChatMessagesCount();
                    if ($newMessageCount > 0) {
                        $userCount+= 1;
                        Mail::to($user->email)
                            ->send(new ChatMessageResumeEmail($user, $newMessageCount));
                    }
                }
            });

        $this->info(sprintf('%s users notified', $userCount));

        return true;
    }
}
