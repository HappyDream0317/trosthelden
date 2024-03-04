<?php

namespace App\Exports;

use App\User;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserStatusReferrerExport implements FromCollection
{
    /** @var Collection */
    private $userList = [];

    /** @var Collection */
    private $allChats = [];

    /** @var Collection */
    private $activeChats = [];

    /** @var Collection */
    private $rejectedChats = [];

    /** @var array */
    private $columns = [
        'ID',
        'Nickname',
        'E-Mail',
        'Role',
        'registered',
        'Last Seen At',
        'Frabo Status',
        'Status',
        'Aktive TP',
        'Referrer',
        'Campaign',
        'Medium',
        'Source',
        'Content',
        'Referring Domain',
        'Keyword',
    ];

    public function collection()
    {
        $this->userList = collect([]);
        $this->userList->add($this->columns);
        $users = $this->query();
        if ($users) {
            foreach ($users as $user) {
                $row = $this->formatRow($user);
                $this->userList->add($row);
            }
        }
        return $this->userList;
    }

    private function query(): Collection
    {
        return User::get()->filter(function ($value, $key) {
                return !$value->has_nova_access &&
                    !strpos($value->email, '@yesdevs.com') &&
                    !strpos($value->email, '@trosthelden.de');
            });
    }

    private function formatRow(User $user): array
    {
        $lastSeenAt = $this->formatDate($user->last_seen_at);
        $createdAt = $this->formatDate($user->created_at);

        $status = $this->getUserStatus($user);

        $this->allChats = $this->createChatStats($user);
        $this->rejectedChats = $this->getRejectedChats($user);
        $this->activeChats = $this->getActiveChats();

        $trostPartnerActiveCount = $this->activeChats->count();

        return [
            $user->id,
            $user->nickname,
            $user->email,
            $user->getRoleName(),
            $createdAt,
            $lastSeenAt,
            $user->matching_step,
            $status,
            $trostPartnerActiveCount,
            isset($user->referrer) ? $user->referrer->referrer : '',
            isset($user->referrer) ? $user->referrer->campaign : '',
            isset($user->referrer) ? $user->referrer->medium : '',
            isset($user->referrer) ? $user->referrer->source : '',
            isset($user->referrer) ? $user->referrer->content : '',
            isset($user->referrer) ? $user->referrer->referring_domain : '',
            isset($user->referrer) ? $user->referrer->keyword : '',
        ];
    }

    private function formatDate($value = null): ?string
    {
        $date = null;
        if($value) {
            $date = new DateTime($value);
            return $date->format('d.m.Y');
        }
        return $date;
    }

    private function getUserStatus(User $user) : string
    {
        return ($user->is_premium) ? 'premium' : 'non-premium';
    }

    private function createChatStats(User $user): Collection
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

                    $stats[$chat->id][$pId] = $chat->messages->filter(fn($msg) => $msg->user_id === $pId)->count();
                }
            }
        }
        return collect([$stats])->mapWithKeys(fn($k) => $k);
    }

    private function getActiveChats(): Collection
    {
        return $this->allChats->filter(function ($chat, $chatId) {
            $msgCounts = array_values($chat);
            return !$this->rejectedChats->has($chatId) && $msgCounts[0] > 0 && $msgCounts[1] > 0;
        });
    }

    private function getRejectedChats(User $user): Collection
    {
        return $this->allChats->map(function ($chat) use ($user) {
            unset($chat[$user->id]);
            return array_values(array_flip($chat))[0];
        })->filter(function ($foreignUserId) use ($user) {
            $friendStatusId = $user->getFriendStatus($foreignUserId);
            return $friendStatusId === 3; // rejected
        });
    }
}