<?php


namespace App\Helpers;

use App\UserFriend;

class FriendshipHelper
{
    /**
     * Get All Friendship DB Entries
     *
     * @param $userId1
     * @param $userId2
     * @return UserFriend[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function fetchFriendship($userId1, $userId2)
    {
        return self::queryFriendship($userId1, $userId2)->get();
    }

    /**
     * Delete Friendship on both sides
     *
     * @param $userId1
     * @param $userId2
     * @return bool|mixed|null
     * @throws \Exception
     */

    public static function deleteFriendship($userId1, $userId2)
    {
        return self::queryFriendship($userId1, $userId2)->delete();
    }

    /**
     * Query Friendship on both sides
     *
     * @param $userId1
     * @param $userId2
     * @return UserFriend|\Illuminate\Database\Eloquent\Builder
     */

    public static function queryFriendship($userId1, $userId2)
    {
        return UserFriend::where([
            ['user_id', '=',  $userId1],
            ['foreign_user_id', '=',  $userId2],
        ])
        ->orWhere([
            ['user_id', '=',  $userId2],
            ['foreign_user_id', '=',  $userId1],
        ]);
    }
}
