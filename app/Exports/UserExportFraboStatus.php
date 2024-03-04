<?php

namespace App\Exports;

use App\MatchingUserPartnerRanking;
use App\User;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExportFraboStatus implements FromCollection
{
    public function collection()
    {
        $userList = collect([]);
        $userList->add(['ID', 'Nickname', 'E-Mail', 'Role', 'Last Seen At', 'Frabo Status']);

        $users = User::get()->filter(function ($value, $key) {
            return !$value->has_nova_access &&
                !strpos($value->email, '@yesdevs.com') &&
                !strpos($value->email, '@trosthelden.de');
        });

//        $users = User::get();

        if ($users) {
            foreach ($users as $user) {

//                if($user->id !== 60) continue;

                $lastSeenAt = null;

                if ($user->last_seen_at) {
                    $date = new \DateTime($user->last_seen_at);
                    $lastSeenAt = $date->format('d.m.Y');
                }

                $row = [
                    $user->id,
                    $user->nickname,
                    $user->email,
                    $user->getRoleName(),

                    $lastSeenAt,
                    $user->matching_step
                ];

                $userList->add($row);
            }
        }

        return $userList;
    }
}
