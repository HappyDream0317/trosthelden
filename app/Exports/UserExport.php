<?php

namespace App\Exports;

use App\MatchingUserPartnerRanking;
use App\User;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromCollection
{
    public function collection()
    {
        $userList = collect([]);
        $userList->add(['E-Mail', 'Nickname', 'Role', 'Last Seen At', 'Matches']);

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

                $lastSeenAt = null;

                if ($user->last_seen_at) {
                    $date = new \DateTime($user->last_seen_at);
                    $lastSeenAt = $date->format('d.m.Y');
                }

                $matches = MatchingUserPartnerRanking::where('user_id', $user->id)
                    ->where('partner_id', '!=', $user->id)
                    ->whereHas('partner', function ($q) {
                        $q->whereNotNull('nickname');
                        $q->whereNotNull('last_seen_at');
                        $q->where('last_seen_at', '>', Carbon::now()->subDays(14));
                    })
                    ->orderBy('rank', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->get();

                $row = [
                    $user->email,
                    $user->nickname,
                    $user->getRoleName(),
                    $lastSeenAt,
                    $matches->count()
                ];

                $userList->add($row);
            }
        }

        return $userList;
    }
}
