<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserInsightsExport implements FromCollection
{
    /** @var Collection */
    private $allChats = [];

    /** @var Collection */
    private $activeChats = [];

    /** @var Collection */
    private $rejectedChats = [];

    private $columns = [
        'Id',
        'E-Mail',
        'Registrierung',
        'Zuletzt Online',
        'Anz. Chats',
        'Active TP',
        'Bestätigte TP',
        'Abgelehnte TPs',
        'Offene, gesendete Anfragen',
        'Offene, erhaltene Anfragen',
        'Nachrichten Total (beidseitig)',
        'Nachrichten Durschnitt (beidseitig)',
        'Nachrichten Median (beidseitig)',
        'Nachrichten Total (user)',
        'Nachrichten Durschnitt (user)',
        'Nachrichten Median (user)'
    ];

    public function collection()
    {
        $userList = collect([]);
        $userList->add($this->columns);

        $users = User::get()->filter(function ($value, $key) {
            return $value->matching_step == -1 &&
                !$value->has_nova_access &&
                !strpos($value->email, '@yesdevs.com') &&
                !strpos($value->email, '@trosthelden.de');
        });

//        $users = User::get();

        if ($users) {
            foreach ($users as $user) {
//                if ($user->id !== 60) {
//                    continue;
//                }

                $lastSeenAt = null;

                if ($user->last_seen_at) {
                    $date = new \DateTime($user->last_seen_at);
                    $lastSeenAt = $date->format('d.m.Y');
                }

                $this->allChats = $this->createChatStats($user);
                $this->rejectedChats = $this->getRejectedChats($user);
                $this->activeChats = $this->getActiveChats(); // Less rejected chats & more than one message sent on both sides

                $trostpartnerRequestSentOpen = $user->friendshipInvitations->count(); // open friend requests sent by the user
                $trostpartnerRequestReceivedOpen = $user->friendshipInvitationsBy->count(); // open friend requests received by other user

                $trostPartnerCount = $user->getChats()->count(); // just count the active Trauerfreund chats
                $trostpartnerRequestSentRejected = $this->rejectedChats->count(); // Number of rejected Friends Requests (Chats)
                $trostpartnerRequestSentConfirmed = $trostPartnerCount - $trostpartnerRequestSentRejected - $trostpartnerRequestSentOpen - $trostpartnerRequestReceivedOpen; // cleaned chat count
                $trostPartnerActiveCount = $this->activeChats->count(); // just count the active Trauerfreund chats

                $trostpartnerMessageCountTotal = $this->getTotalMessageCountOfActiveChats(); // count all messages in all chats
                $trostpartnerMessageCountAverage = $this->getAvgMessageCountPerActiveChat(); // calculate the average value of each chat
                $trostpartnerMessageCountMedian = $this->getMedianMessageCountPerActiveChat(); // calculate the median value of each chat

                $trostpartnerMessageCountTotalByUser = $this->getTotalMessageCountOfActiveChatsByUser($user); //  count all messages by user in all chats
                $trostpartnerMessageCountAverageByUser = $this->getAvgMessageCountPerActiveChatByUser($user); // calculate the average by user of each chat
                $trostpartnerMessageCountMedianByUser = $this->getMedianMessageCountPerActiveChatByUser($user); // calculate the median by user of each chat

                $row = [
                    $user->id,
                    $user->email,
                    $user->created_at,
                    $lastSeenAt,
                    $trostPartnerCount,
                    $trostPartnerActiveCount,
                    $trostpartnerRequestSentConfirmed,
                    $trostpartnerRequestSentRejected,
                    $trostpartnerRequestSentOpen,
                    $trostpartnerRequestReceivedOpen,
                    $trostpartnerMessageCountTotal,
                    $trostpartnerMessageCountAverage ? number_format($trostpartnerMessageCountAverage, 1, ',', '') : null,
                    $trostpartnerMessageCountMedian,
                    $trostpartnerMessageCountTotalByUser,
                    $trostpartnerMessageCountAverageByUser ? number_format($trostpartnerMessageCountAverageByUser, 1, ',', '') : null,
                    $trostpartnerMessageCountMedianByUser
                ];

                $userList->add($row);
            }
        }

        return $userList;
    }

    private function createChatStats(User $user)
    {
        $stats = [];
        $chats = $user->getChats();
        if ($chats) {
            foreach ($chats as $chat) {
                if (!isset($stats[$chat->id])) {
                    $stats[$chat->id] = [];
                }

                foreach ($chat->participants as $pId) { // $pId = participant Id
                    if (!isset($stats[$chat->id][$pId])) {
                        $stats[$chat->id][$pId] = 0;
                    }

                    $stats[$chat->id][$pId] = $chat->messages->filter(fn ($msg) => $msg->user_id === $pId)->count();
                }
            }
        }
        return collect([$stats])->mapWithKeys(fn ($k) => $k);
    }

    private function getActiveChats()
    {
        return $this->allChats->filter(function ($chat, $chatId) {
            $msgCounts = array_values($chat);
            return !$this->rejectedChats->has($chatId) && $msgCounts[0] > 0 && $msgCounts[1] > 0;
        });
    }

    private function getRejectedChats(User $user)
    {
        return $this->allChats->map(function ($chat) use ($user) {
            unset($chat[$user->id]);
            return array_values(array_flip($chat))[0];
        })->filter(function ($foreignUserId) use ($user) {
            $friendStatusId = $user->getFriendStatus($foreignUserId);
            return $friendStatusId === 3; // rejected
        });
    }

    private function getTotalMessageCountOfActiveChats()
    {
        return $this->activeChats->map(function ($chat) {
            $msgCounts = array_values($chat);
            if ($msgCounts[0] > 0 && $msgCounts[1] > 0) {
                return $msgCounts[0] + $msgCounts[1];
            }
            return false;
        })->sum();
    }

    private function getAvgMessageCountPerActiveChat()
    {
        return $this->activeChats->map(function ($chat) {
            $msgCounts = array_values($chat);
            if ($msgCounts[0] > 0 && $msgCounts[1] > 0) {
                return $msgCounts[0] + $msgCounts[1];
            }
            return false;
        })->average();
    }

    private function getMedianMessageCountPerActiveChat()
    {
        return $this->activeChats->map(function ($chat) {
            $msgCounts = array_values($chat);
            if ($msgCounts[0] > 0 && $msgCounts[1] > 0) {
                return $msgCounts[0] + $msgCounts[1];
            }
            return false;
        })->median();
    }

    private function getTotalMessageCountOfActiveChatsByUser(User $user)
    {
        return $this->activeChats->map(fn ($chat) => $chat[$user->id])->sum();
    }

    private function getAvgMessageCountPerActiveChatByUser(User $user)
    {
        return $this->activeChats->map(fn ($chat) => $chat[$user->id])->average();
    }

    private function getMedianMessageCountPerActiveChatByUser(User $user)
    {
        return $this->activeChats->map(fn ($chat) => $chat[$user->id])->median();
    }
}
