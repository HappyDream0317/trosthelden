<?php

namespace App\Helpers;

use App\UserFriendRequest;

class FriendRequestHelper
{
    public static function fetchFriendRequest($userId1, $userId2)
    {
        return self::queryFriendRequest($userId1, $userId2)->get();
    }

    /**
     * The query in acceptFriendRequest did not work,
     * that's why there are still friend requests in the db
     * and we need a hotfix called "EnsureAllFriendRequestsAreDeleted" in the User Model
     *
     * @param $userId1
     * @param $userId2
     * @return mixed
     *
     */
    public static function deleteAllFriendRequests($userId1, $userId2)
    {
        return self::queryFriendRequest($userId1, $userId2)->delete();
    }

    /**
     * Fetch Friend Requests on both sides
     *
     * @param $userId1
     * @param $userId2
     * @return mixed
     */
    public static function queryFriendRequest($userId1, $userId2)
    {
        return UserFriendRequest::where([
            ['user_id', '=',  $userId1],
            ['added_user_id', '=',  $userId2],
        ])
        ->orWhere([
            ['user_id', '=',  $userId2],
            ['added_user_id', '=',  $userId1],
        ]);
    }
}
