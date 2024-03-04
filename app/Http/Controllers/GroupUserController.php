<?php

namespace App\Http\Controllers;

use App\GroupUser;

class GroupUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getUsersInGroup($group_id)
    {
        return GroupUser::where('group_id', $group_id);
    }

    public function getGroupsFromUser($user_id)
    {
        return GroupUser::where('user_id', $user_id);
    }
}
