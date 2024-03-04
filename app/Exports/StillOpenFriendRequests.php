<?php

namespace App\Exports;

use App\MatchingUserPartnerRanking;
use App\User;
use App\UserFriend;
use App\UserFriendRequest;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class StillOpenFriendRequests implements FromCollection
{
    private $friendships;

    public function collection()
    {
        $result = collect([]);
        $result->add(['ID', 'date', 'User ID', 'User Nickname', 'Added User ID', 'Added User Nickname']);

        $friendRequests = UserFriendRequest::with('user', 'invited')->get();
        $this->friendships = UserFriend::get()->map(function ($friendship) {
            return [$friendship->user_id => $friendship->foreign_user_id];
        });

        $stillOpenRequests = $friendRequests->map(function ($request) {
            $userId = $request->user_id;
            $addedUserId = $request->added_user_id;

            $exists1 = $this->checkIfFriendshipExists($userId, $addedUserId);
            $exists2 = $this->checkIfFriendshipExists($addedUserId, $userId);

            if ($exists1->count() || $exists2->count()) {
                return $request;
            }
        })
        ->filter(fn ($value) => $value !== null);

        $stillOpenRequests->each(fn ($request) => $result->add([
            $request->id,
            $request->created_at,
            $request->user_id,
            $request->user->nickname,
            $request->added_user_id,
            $request->invited->nickname,
        ]));

        return $result;
    }

    private function checkIfFriendshipExists($userId1, $userId2)
    {
        return $this->friendships->filter(function ($array) use ($userId1, $userId2) {
            $userId = array_key_first($array);
            $foreignUserId = $array[$userId];
            return $userId === $userId1 && $foreignUserId === $userId2;
        });
    }
}
