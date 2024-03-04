<?php

namespace App\Exports;

use App\MatchingAnswer;
use App\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserMessageExport implements FromCollection
{
    public function collection()
    {
        $userList = collect([]);
        $userList->add(['Id', 'E-Mail', 'Chats', 'More than 3 Messages', 'Max Message Count']);

        $users = User::get()->filter(function ($value, $key) {
            return $value->matching_step == -1 &&
                !$value->has_nova_access &&
                !strpos($value->email, '@yesdevs.com') &&
                !strpos($value->email, '@trosthelden.de');
        });

//        $users = User::get();

        if ($users) {
            foreach ($users as $user) {

//                if($user->id !== 60) continue;

                $userMessageCount = [];
                $userChats = $user->getChats();

                foreach ($userChats as $chat) {
                    $participants = $chat->participants;
                    $ownUserKey = array_search($user->id, $participants);
                    unset($participants[$ownUserKey]);

                    $participantId = array_values($participants)[0];

                    foreach ($chat->messages()->get() as $message) {
                        if ($message->user_id !== $user->id) {
                            continue;
                        }
                        if (!array_key_exists($participantId, $userMessageCount)) {
                            $userMessageCount[$participantId] = 0;
                        }

                        $userMessageCount[$participantId]++;
                    }
                }

                $partnerColumn = [];
                foreach ($userMessageCount as $userId => $msgCount) {
                    if ($msgCount >= 3) {
                        $partner = User::find($userId);
                        if ($partner && $partner->email) { // avoid delted users
                            array_push($partnerColumn, $partner->email);
                        }
                    }
                }

                $row = [
                    $user->id, // Id
                    $user->email, // Username
                    count($userMessageCount), // Anzahl der Mitglieder, an die mindestens eine Nachrichten versandt wurde
                    implode(' | ', $partnerColumn), // Nutzer mit mindestens 3 Nachrichten mit einer Person
                    !empty($userMessageCount) ? max($userMessageCount) : 0 // Maximale Anzahl der versandten Nachricht an ein Mitglied
                ];

                $userList->add($row);
            }
        }

        return $userList;
    }
}
